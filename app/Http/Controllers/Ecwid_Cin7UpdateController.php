<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Helpers\SkuTransformer; // Import the helper class

class Ecwid_Cin7UpdateController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchOrder($type = 'all')
    {
        $storeId = env('ECWID_STORE_ID');
        $apiToken = env('ECWID_API_TOKEN');

        $url = "https://app.ecwid.com/api/v3/{$storeId}/orders";
        $queryParams = [
            'count' => 100,
            'paymentStatus' => 'PAID',
            'fulfillmentStatus' => 'AWAITING_PROCESSING,PROCESSING,SHIPPED',
        ];

        try {
            Log::info('Fetching orders from Ecwid', ['url' => $url, 'queryParams' => $queryParams]);

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$apiToken}",
                ],
                'query' => $queryParams
            ]);

            $jsonData = json_decode($response->getBody()->getContents(), true);

            Log::info('Orders fetched successfully', ['orders' => $jsonData]);

            $extractedData = array_map(function ($order) {
                return [
                    'orderNumber' => $order['id'],
                    'total' => $order['total'],
                    'orderComments' => isset($order['orderComments']) ? $order['orderComments'] : 'NoCustomerComments',
                    'createDate' => $order['createDate'],
                    'updateDate' => $order['updateDate'],
                    'items' => array_map(function ($item) {
                        if (($item['name'] == "Test tees JIC") || ($item['name'] == "Test tees BSPT")) {
                            $item['new_sku'] = $item['sku'];
                        } else {
                            $item['new_sku'] = SkuTransformer::transform($item['selectedOptions'] ?? []);
                        }
                        return [
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'sku' => $item['sku'],
                            'new-sku' => $item['new_sku'],
                            'price' => $item['price'],
                            'nameTranslated' => isset($item['nameTranslated']['en']) ? $item['nameTranslated']['en'] : null,
                            'selectedOptions' => isset($item['selectedOptions']) ? array_map(function ($option) {
                                return [
                                    'name' => $option['name'],
                                    'value' => $option['value']
                                ];
                            }, $item['selectedOptions']) : []
                        ];
                    }, $order['items']),
                    'shippingPerson' => isset($order['shippingPerson']) ? [
                        'fullName' => $order['shippingPerson']['name'],
                        'email' => isset($order['email']) ? $order['email'] : null,
                        'phone' => $order['shippingPerson']['phone'],
                        'street' => $order['shippingPerson']['street'],
                        'city' => $order['shippingPerson']['city'],
                        'stateOrProvinceName' => $order['shippingPerson']['stateOrProvinceName'],
                        'postalCode' => $order['shippingPerson']['postalCode'],
                        'countryName' => $order['shippingPerson']['countryName'],
                    ] : null,
                    'shippingOption' => isset($order['shippingOption']) ? [
                        'shippingCarrierName' => isset($order['shippingOption']['shippingCarrierName']) ? $order['shippingOption']['shippingCarrierName'] : 'defaultCarrierName',
                        'shippingMethodName' => isset($order['shippingOption']['shippingMethodName']) ? $order['shippingOption']['shippingMethodName'] : 'defaultCarriermethod',
                        'shippingRate' => isset($order['shippingOption']['shippingRate']) ? $order['shippingOption']['shippingRate'] : 'defaultRate',
                    ] : null,
                    'billingPerson' => isset($order['billingPerson']) ? [
                        'fullName' => $order['billingPerson']['name'],
                        'email' => isset($order['email']) ? $order['email'] : null,
                        'phone' => $order['billingPerson']['phone'],
                        'street' => $order['billingPerson']['street'],
                        'city' => $order['billingPerson']['city'],
                        'stateOrProvinceName' => $order['billingPerson']['stateOrProvinceName'],
                        'postalCode' => $order['billingPerson']['postalCode'],
                        'country' => $order['billingPerson']['countryName']
                    ] : null,
                    'payment' => [
                        'paymentMethod' => $this->getPaymentMethod($order['paymentMethod']),
                        'paymentId' => isset($order['externalTransactionId']) ? $this->getPaymentMethod($order['externalTransactionId']) : '',
                    ],
                    'additionalInformation' => [
                        'paymentMethod' => $this->getPaymentMethod($order['ipAddress']),
                    ]
                ];
            }, $jsonData['items']);

            Log::info('Orders processed successfully');

            if (!empty($extractedData)) {
                $this->createCustomerForCin7($extractedData[4]);
            }

            if ($type == 'first') {
                return response()->json($extractedData[0], 200);
            } elseif ($type == 'last') {
                return response()->json(end($extractedData), 200);
            }

            return response()->json($extractedData, 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch orders', 'message' => $e->getMessage()], 500);
        }
    }

    public function createCustomerForCin7($order)
    {
        Log::info('Create Customer Request:', ['context' => "Am on CreateCustomer method"]);

        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';

        $data = [
            "Name" => $order['shippingPerson']['fullName'],
            "Currency" => "AUD",
            "PaymentTerm" => "30 days",
            "Discount" => 0,
            "TaxRule" => "GST on Income",
            "Carrier" => "DEFAULT Carrier",
            "SalesRepresentative" => "DEFAULT billing contact",
            "Location" => "Main Warehouse",
            "Comments" => null,
            "AccountReceivable" => "610",
            "RevenueAccount" => "200",
            "PriceTier" => "Tier 1",
            "TaxNumber" => null,
            "AttributeSet" => null,
            "Tags" => null,
            "Status" => "Active",
            "IsOnCreditHold" => true,
            "Addresses" => [
                [
                    "Line1" => $order['shippingPerson']['street'],
                    "Line2" => '',
                    "City" => $order['shippingPerson']['city'],
                    "State" => $order['shippingPerson']['stateOrProvinceName'],
                    "Postcode" => $order['shippingPerson']['postalCode'],
                    "Country" => $order['shippingPerson']['countryName'],
                    "Type" => "Business",
                    "DefaultForType" => true
                ],
                [
                    "Line1" => $order['billingPerson']['street'],
                    "Line2" => '',
                    "City" => $order['billingPerson']['city'],
                    "State" => $order['billingPerson']['stateOrProvinceName'],
                    "Postcode" => $order['billingPerson']['postalCode'],
                    "Country" => $order['billingPerson']['country'],
                    "Type" => "Billing",
                    "DefaultForType" => true
                ]
            ],
            "Contacts" => [
                [
                    "Name" => $order['billingPerson']['fullName'],
                    "JobTitle" => null,
                    "Phone" => $order['billingPerson']['phone'],
                    "MobilePhone" => null,
                    "Fax" => "034389379", // Default Fax as it's not provided in the payload
                    "Email" => $order['billingPerson']['email'],
                    "Website" => null,
                    "Default" => true,
                    "Comment" => null,
                    "IncludeInEmail" => false
                ]
            ]
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];

        try {
            // Check if the customer already exists
            $existingCustomerResponse = $client->get($url, [
                'headers' => $headers,
                'query' => ['Name' => $order['shippingPerson']['fullName']]
            ]);

            $existingCustomerData = json_decode($existingCustomerResponse->getBody()->getContents(), true);

            if (!empty($existingCustomerData['CustomerList'])) {
                $customer = $existingCustomerData['CustomerList'][0];
            } else {
                // Create a new customer
                $response = $client->post($url, [
                    'headers' => $headers,
                    'json' => $data
                ]);

                $body = $response->getBody();
                $customer = json_decode($body->getContents(), true)['CustomerList'][0];
            }

            // Generate sale order using the customer details
            $saleOrder = $this->generateSale($customer, $order);

            return response()->json($customer);
        } catch (\Exception $e) {
            Log::error('Error creating or fetching customer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
     
    public function generateSale($customer, $order)
    {
        Log::info('Generating Sale Order for Customer:', ['customerId' => $customer['ID']]);
    
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale';
    
        // Fetch ProductID for each item based on SKU
        $lines = array_map(function ($item) {
            $productID = $this->getProductIDBySKU($item['new-sku']);
            return [
                "ProductID" => $productID,
                "SKU" => $item['new-sku'],
                "Name" => $item['name'],
                "Quantity" => $item['quantity'],
                "Price" => $item['price'],
                "Discount" => 0,
                "Tax" => 0,
                "AverageCost" => $item['AverageCost'] ?? 0,
                "TaxRule" => "GST on Income",
                "Comment" => "",
                "Total" => $item['quantity'] * $item['price']
            ];
        }, $order['items']);
    
        $data = [
            "CustomerID" => $customer['ID'],
            "Customer" => $customer['Name'],
            "Phone" => $customer['Contacts'][0]['Phone'],
            "Email" => $customer['Contacts'][0]['Email'],
            "Contact" => $customer['Contacts'][0]['Name'],
            "DefaultAccount" => $customer['RevenueAccount'],
            "Status" => "COMPLETED",
            "CombinedPickingStatus" => "PICKING",
            "CombinedPackingStatus" => "PACKING",
            "CombinedShippingStatus" => "SHIPPING",
            "BillingAddress" => [
                "Line1" => $customer['Addresses'][0]['Line1'],
                "Line2" => $customer['Addresses'][0]['Line2'],
                "City" => $customer['Addresses'][0]['City'],
                "State" => $customer['Addresses'][0]['State'],
                "Postcode" => $customer['Addresses'][0]['Postcode'],
                "Country" => $customer['Addresses'][0]['Country']
            ],
            "ShippingAddress" => [
                "Line1" => $customer['Addresses'][1]['Line1'],
                "Line2" => $customer['Addresses'][1]['Line2'],
                "City" => $customer['Addresses'][1]['City'],
                "State" => $customer['Addresses'][1]['State'],
                "Postcode" => $customer['Addresses'][1]['Postcode'],
                "Country" => $customer['Addresses'][1]['Country'],
                "Company" => "Australia BEST Hydraulic Services",
                "Contact" => "Sheree Bond test",
                "ShipToOther" => false
            ],
            "Quote" => [
                "Memo" => $order['orderComments'],
                "Status" => "AUTHORISED",
                "Prepayments" => [],
                "Lines" => $lines,
                "AdditionalCharges" => [],
                "TotalBeforeTax" => $order['total'],
                "Tax" => 0,
                "Total" => $order['total']
            ],
            "Order" => [
                "Memo" => $order['orderComments'],
                "Status" => "AUTHORISED",
                "Lines" => $lines,
                "AdditionalCharges" => [],
                "TotalBeforeTax" => $order['total'],
                "Tax" => 0,
                "Total" => $order['total']
            ],
            "Invoices" => [
                [
                    "TaskID" => "",
                    "Status" => "AUTHORISED",
                    "InvoiceDate" => now()->toIso8601String(),
                    "InvoiceDueDate" => now()->addDays(30)->toIso8601String(),
                    "Lines" => $lines,
                    "AdditionalCharges" => [],
                    "TotalBeforeTax" => $order['total'],
                    "Tax" => 0,
                    "Total" => $order['total']
                ]
            ],
            "ShippingNotes" => "",
            "TaxRule" => "GST on Income",
            "TaxInclusive" => "false",
            "Terms" => $customer['PaymentTerm'],
            "PriceTier" => $customer['PriceTier'],
            "Location" => $customer['Location'],
            "Note" => "",
            "CustomerReference" => "",
            "AutoPickPackShipMode" => "NOPICK",
            "SalesRepresentative" => $customer['SalesRepresentative'],
            "Carrier" => $customer['Carrier'],
            "CurrencyRate" => "1",
            "ShipBy" => now()->addDays(10)->toIso8601String(),
            "SaleOrderDate" => now()->toIso8601String(),
            "SkipQuote" => "false",
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);
    
            $body = $response->getBody();
            $content = json_decode($body->getContents(), true);
            Log::info('Sale Order Created: ', $content);
    
            // Extract SaleID from response and call saleQuote API
            if (isset($content['ID'])) {
                $this->createSaleQuote($content['ID'], $order['items']);
            }
    
            return $content;
        } catch (\Exception $e) {
            Log::error('Error creating sale order: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function createSaleQuote($saleId, $items)
    {
        Log::info('Creating Sale Quote for SaleID:', ['saleId' => $saleId]);
    
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale/quote';
    
        $lines = array_map(function ($item) {
            $productID = $this->getProductIDBySKU($item['new-sku']);
            return [
                "ProductID" => $productID,
                "SKU" => $item['new-sku'],
                "Name" => $item['name'],
                "Quantity" => $item['quantity'],
                "Price" => $item['price'],
                "Discount" => "",
                "Tax" => "",
                "AverageCost" => $item['AverageCost'] ?? 0,
                "TaxRule" => "GST on Income",
                "Comment" => "",
                "Total" => $item['quantity'] * $item['price']
            ];
        }, $items);
    
        Log::info('Lines for Sale Quote:', ['lines' => $lines]);
    
        $data = [
            "SaleID" => $saleId,
            "CombineAdditionalCharges" => false,
            "Memo" => "",
            "Status" => "AUTHORISED",
            "Prepayments" => [],
            "Lines" => $lines,
            "AdditionalCharges" => [],
            "TotalBeforeTax" => "",
            "Tax" => "",
            "Total" =>"",
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);
    
            $body = $response->getBody();
            $content = json_decode($body->getContents(), true);
            Log::info('Sale Quote Created: ', $content);
    
            // Extract TaskID from response and call saleOrder API
            if (isset($content['TaskID'])) {
                $this->createSaleOrder($saleId, $items);
            }
    
            return $content;
        } catch (\Exception $e) {
            Log::error('Error creating sale quote: ' . $e->getMessage());
            Log::error('Response Body: ' . $e->getResponse()->getBody()->getContents());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function createSaleOrder($saleId, $items)
    {
        Log::info('Creating Sale Order for SaleID:', ['saleId' => $saleId]);
    
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale/order';
    
        $lines = array_map(function ($item) {
            $productID = $this->getProductIDBySKU($item['sku']);
            return [
                "ProductID" => $productID,
                "SKU" => $item['sku'],
                "Name" => $item['name'],
                "Quantity" => $item['quantity'],
                "Price" => $item['price'],
                "Discount" => 0,
                "Tax" => 0,
                "AverageCost" => $item['AverageCost'] ?? 0,
                "TaxRule" => "GST on Income",
                "Comment" => "",
                "DropShip" => false,
                "BackorderQuantity" => 0,
                "Total" => $item['quantity'] * $item['price']
            ];
        }, $items);
    
        Log::info('Lines for Sale Order:', ['lines' => $lines]);
    
        $data = [
            "SaleID" => $saleId,
            "CombineAdditionalCharges" => False,
            "Memo" => "",
            "Status" => "AUTHORISED",
            "AutoPickPackShipMode" => "NOPICK",
            "Lines" => $lines,
            "AdditionalCharges" => [],
            "TotalBeforeTax" => array_sum(array_column($lines, 'Total')) + 350,
            "Tax" => 0,
            "Total" => array_sum(array_column($lines, 'Total')) + 350
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);
    
            $body = $response->getBody();
            $content = json_decode($body->getContents(), true);
            Log::info('Sale Order Created: ', $content);
    
            // Extract TaskID from response and call saleInvoice API
            if (isset($content['TaskID'])) {
                $this->createSaleInvoice($saleId, $content['TaskID'], $items);
            }
    
            return $content;
        } catch (\Exception $e) {
            Log::error('Error creating sale order: ' . $e->getMessage());
            Log::error('Response Body: ' . $e->getResponse()->getBody()->getContents());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }






    
    public function createSaleInvoice($saleId, $taskId, $items)
    {
        Log::info('Creating Sale Invoice for SaleID:', ['saleId' => $saleId, 'taskId' => $taskId]);
    
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale/invoice';
    
        $lines = array_map(function ($item) {
            $productID = $this->getProductIDBySKU($item['sku']);
            return [
                "ProductID" => $productID,
                "SKU" => $item['new-sku'],
                "Name" => $item['name'],
                "Quantity" => $item['quantity'],
                "Price" => $item['price'],
                "Discount" => 0,
                "Tax" => 0,
                "Total" => $item['quantity'] * $item['price'],
                "TaxRule" => "GST on Income",
                "Account" => "200",
                "Comment" => ""
            ];
        }, $items);
    
        Log::info('Lines for Sale Invoice:', ['lines' => $lines]);
    
        $data = [
            "SaleID" => $saleId,
            "TaskID" => $taskId,
            "CombineAdditionalCharges" => false,
            "Memo" => "",
            "Status" => "DRAFT",
            "InvoiceDate" => now()->toIso8601String(),
            "InvoiceDueDate" => now()->addDays(30)->toIso8601String(),
            "CurrencyConversionRate" => 1,
            "BillingAddressLine1" => "3 Park Street Industrial Village Southbank",
            "BillingAddressLine2" => "Melbourne VIC 3331",
            "LinkedFulfillmentNumber" => "1",
            "Lines" => $lines,
            "AdditionalCharges" => []
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);
    
            $body = $response->getBody();
            $content = json_decode($body->getContents(), true);
            Log::info('Sale Invoice Created: ', $content);
    
            return $content;
        } catch (\Exception $e) {
            Log::error('Error creating sale invoice: ' . $e->getMessage());
            Log::error('Response Body: ' . $e->getResponse()->getBody()->getContents());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    private function getProductIDBySKU($sku)
    {
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/product';
    
        $queryParams = [
            'SKU' => $sku
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'query' => $queryParams
            ]);
    
            $body = $response->getBody();
            $content = json_decode($body->getContents(), true);
    
            if (isset($content['Products']) && !empty($content['Products'])) {
                return $content['Products'][0]['ID'];
            }
    
            Log::error('ProductID not found for SKU: ' . $sku);
            return '';
        } catch (\Exception $e) {
            Log::error('Error fetching ProductID for SKU: ' . $sku . ' - ' . $e->getMessage());
            return '';
        }
    }
    
    
    
    
    
    

    private function getPaymentMethod($paymentMethod)
    {
        if (stripos($paymentMethod, 'credit') !== false || stripos($paymentMethod, 'debit') !== false) {
            return 'stripe';
        } elseif (stripos($paymentMethod, 'paypal') !== false) {
            return 'paypal';
        } else {
            return $paymentMethod; // Return the original payment method if no match
        }
    }

    public function garry()
    {
        return "test";
    }
}
