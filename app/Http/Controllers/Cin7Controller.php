<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\EcwidController;

class Cin7Controller extends Controller
{
    protected $ecwidController;

    // Inject the EcwidController into the Cin7Controller
    public function __construct(EcwidController $ecwidController)
    {
        $this->ecwidController = $ecwidController;
    }

    // public function createSale(Request $request)
    // {
    //     Log::info('Create Sale Request:', $request->all());

    //     $client = new Client();
    //     $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale';  // Replace with the actual endpoint URL

    //     $data = [
    //         "CustomerID" => $request->input('CustomerID'),
    //         "Customer" => $request->input('Customer'),
    //         "Phone" => $request->input('Phone'),
    //         "Email" => $request->input('Email'),
    //         "Contact" => $request->input('Contact'),
    //         "DefaultAccount" => $request->input('DefaultAccount'),
    //         "BillingAddress" => $request->input('BillingAddress'),
    //         "ShippingAddress" => $request->input('ShippingAddress'),
    //         "ShippingNotes" => $request->input('ShippingNotes'),
    //         "TaxRule" => $request->input('TaxRule'),
    //         "TaxInclusive" => $request->input('TaxInclusive'),
    //         "Terms" => $request->input('Terms'),
    //         "PriceTier" => $request->input('PriceTier'),
    //         "Location" => $request->input('Location'),
    //         "Note" => $request->input('Note'),
    //         "CustomerReference" => $request->input('CustomerReference'),
    //         "AutoPickPackShipMode" => $request->input('AutoPickPackShipMode'),
    //         "SalesRepresentative" => $request->input('SalesRepresentative'),
    //         "Carrier" => $request->input('Carrier'),
    //         "CurrencyRate" => $request->input('CurrencyRate'),
    //         "ShipBy" => $request->input('ShipBy'),
    //         "SaleOrderDate" => $request->input('SaleOrderDate'),
    //         "SkipQuote" => $request->input('SkipQuote'),
    //     ];

    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'api-auth-accountid' => 'your_account_id',  // Replace with your actual account ID
    //         'api-auth-applicationkey' => 'your_application_key'  // Replace with your actual application key
    //     ];

    //     try {
    //         Log::info(__FILE__.__LINE__);

    //         $response = $client->post($url, [
    //             'headers' => $headers,
    //             'json' => $data
    //         ]);

    //         Log::info(__FILE__.__LINE__);

    //         $body = $response->getBody();
    //         $content = json_decode($body, true);
    //         Log::info(__FILE__.__LINE__);

    //         return response()->json($content);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating sale: ' . $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function createCustomerForCin7()
    {
        // Extract order data from request
        $response = $this->ecwidController->fetchOrders('first')->getData();
        return $this->createCustomerInternal($response);
    }

    public function createCustomerInternal($order)
    {
        Log::info('Create Customer Request:', "Am on Ecwid controller");

        // return $order;

        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';

        $data = [
            "Name" => "Naushehra Hydraulic Services",
            "Currency" => "AUD",
            "PaymentTerm" => "30 days",
            "Discount" => 0,
            "TaxRule" => "GST on Income",
            "Carrier" => "DEFAULT Carrier",
            "SalesRepresentative" => null,
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
            'api-auth-accountid' => env('DEAR_ACCOUNT_ID'),
            'api-auth-applicationkey' => env('DEAR_APPLICATION_KEY')
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $body = $response->getBody();
            $content = json_decode($body, true);
            Log::info('Customer Created: ', $content);

            return response()->json($content);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function test(){
    //     // $order = $request->all();
    //     // $FetchAllDataFromEcwid = $this->getResponseFromEcwid();
    //     // return $FetchAllDataFromEcwid;
        
    //     // Log the incoming request
    //     Log::info('Create Customer Request:', $order);

    //     // Prepare data for Cin7 customer creation
    //     $data = [
    //         "Name" => "Australia - Hydraulic Services",
    //         "Currency" => "AUD",
    //         "PaymentTerm" => "30 days",
    //         "Discount" => 0,
    //         "TaxRule" => "GST on Income",
    //         "Carrier" => "DEFAULT Carrier",
    //         "SalesRepresentative" => null,
    //         "Location" => "Main Warehouse",
    //         "Comments" => null,
    //         "AccountReceivable" => "610",
    //         "RevenueAccount" => "200",
    //         "PriceTier" => "Tier 1",
    //         "TaxNumber" => null,
    //         "AttributeSet" => null,
    //         "Tags" => null,
    //         "Status" => "Active",
    //         "IsOnCreditHold" => true,
    //         "Addresses" => [
    //             [
    //                 "Line1" => $order['shippingPerson']['street'],
    //                 "Line2" => '',
    //                 "City" => $order['shippingPerson']['city'],
    //                 "State" => $order['shippingPerson']['stateOrProvinceName'],
    //                 "Postcode" => $order['shippingPerson']['postalCode'],
    //                 "Country" => $order['shippingPerson']['countryName'],
    //                 "Type" => "Business",
    //                 "DefaultForType" => true
    //             ],
    //             [
    //                 "Line1" => $order['billingPerson']['street'],
    //                 "Line2" => '',
    //                 "City" => $order['billingPerson']['city'],
    //                 "State" => $order['billingPerson']['stateOrProvinceName'],
    //                 "Postcode" => $order['billingPerson']['postalCode'],
    //                 "Country" => $order['billingPerson']['country'],
    //                 "Type" => "Billing",
    //                 "DefaultForType" => true
    //             ]
    //         ],
    //         "Contacts" => [
    //             [
    //                 "Name" => $order['billingPerson']['fullName'],
    //                 "JobTitle" => null,
    //                 "Phone" => $order['billingPerson']['phone'],
    //                 "MobilePhone" => null,
    //                 "Fax" => "03 4389379", // Default Fax as it's not provided in the payload
    //                 "Email" => $order['billingPerson']['email'],
    //                 "Website" => null,
    //                 "Default" => true,
    //                 "Comment" => null,
    //                 "IncludeInEmail" => false
    //             ]
    //         ]
    //     ];

    //     // Set headers
    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'api-auth-accountid' => env('CIN7_ACCOUNT_ID'),
    //         'api-auth-applicationkey' => env('CIN7_API_KEY')
    //     ];

    //     // Make HTTP request to create customer in Cin7
    //     try {
    //         $client = new Client();
    //         $response = $client->post('https://inventory.dearsystems.com/ExternalApi/v2/customer', [
    //             'headers' => $headers,
    //             'json' => $data
    //         ]);

    //         // Log and return response
    //         $body = $response->getBody();
    //         $content = json_decode($body, true);
    //         Log::info('Customer Created: ', $content);

    //         return response()->json($content);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating customer: ' . $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function createCustomer(Request $request) // that method for postman testing
    {
        
        Log::info('Create Customer Request:', $request->all());

        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';

        $data = [
            "Name" => "NEWZEALAND Hydraulic POWER SYSTEM TESTING",
            "Currency" => "AUD",
            "PaymentTerm" => "30 days",
            "Discount" => 0,
            "TaxRule" => "GST on Income",
            "Carrier" => "DEFAULT Carrier",
            "SalesRepresentative" => "Garry -",
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
                    "Line1" => "L9, Gurminder street",
                    "Line2" => "16 Gallery view Ave",
                    "City" => "Adelaide",
                    "State" => "SA",
                    "Postcode" => "3131",
                    "Country" => "Australia",
                    "Type" => "Business",
                    "DefaultForType" => true
                ],
                [
                    "Line1" => "L9, Southbank Street",
                    "Line2" => "16 Gallery Ave",
                    "City" => "Adelaide",
                    "State" => "SA",
                    "Postcode" => "3131",
                    "Country" => "Australia",
                    "Type" => "Billing",
                    "DefaultForType" => true
                ]
            ],
            "Contacts" => [
                [
                    "Name" => "Daniel Martin",
                    "JobTitle" => null,
                    "Phone" => "04504387898",
                    "MobilePhone" => null,
                    "Fax" => "038477885",
                    "Email" => "accountt887@diisr.govt",
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
            Log::info(__FILE__.__LINE__);

            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);

            Log::info(__FILE__.__LINE__);

            $body = $response->getBody();
            $content = json_decode($body, true);
            Log::info(__FILE__.__LINE__);

            return response()->json($content);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function getResponseFromEcwid()
    // {
    //     // Call the fetchOrders method from EcwidController
    //     $response = $this->ecwidController->fetchOrders();

    //     // Process the response if needed
    //     return response()->json($response, 200);
    // }


    public function createSale(Request $request) // Method for Postman testing
    {
        Log::info('Create Sale Request:', $request->all());
    
        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/sale';
    
        // Extract order items from request
        $orderItems = $request->input('items', []);
    
        // Prepare the lines array with order items
        $lines = array_map(function($item) {
            return [
                "SKU" => $item['sku'],
                "Product" => $item['name'],
                "Quantity" => $item['quantity'],
                "Price" => $item['price'],
                "Total" => $item['quantity'] * $item['price'],
                "Discount" => 0
            ];
        }, $orderItems);
    
        // Prepare additional charges
        $additionalCharges = [
            [
                "Name" => "Shipping",
                "Quantity" => 1,
                "Price" => $request->input('shippingRate', 0),
                "Total" => $request->input('shippingRate', 0),
                "Discount" => 0,
                "Tax" => 0,
                "TaxRule" => "GST on Income"
            ]
        ];
    
        $data = [
            "CustomerID" => $request->input('CustomerID'),
            "Customer" => $request->input('Customer'),
            "Phone" => $request->input('Phone'),
            "Email" => $request->input('Email'),
            "Contact" => $request->input('Contact'),
            "DefaultAccount" => $request->input('DefaultAccount'),
            "BillingAddress" => $request->input('BillingAddress'),
            "ShippingAddress" => $request->input('ShippingAddress'),
            "ShippingNotes" => $request->input('ShippingNotes'),
            "TaxRule" => $request->input('TaxRule'),
            "TaxInclusive" => $request->input('TaxInclusive'),
            "Terms" => $request->input('Terms'),
            "PriceTier" => $request->input('PriceTier'),
            "Location" => $request->input('Location'),
            "Note" => $request->input('Note'),
            "CustomerReference" => $request->input('CustomerReference'),
            "AutoPickPackShipMode" => $request->input('AutoPickPackShipMode'),
            "SalesRepresentative" => $request->input('SalesRepresentative'),
            "Carrier" => $request->input('Carrier'),
            "CurrencyRate" => $request->input('CurrencyRate'),
            "ShipBy" => $request->input('ShipBy'),
            "SaleOrderDate" => $request->input('SaleOrderDate'),
            "SkipQuote" => $request->input('SkipQuote'),
            "Order" => [
                "SaleOrderNumber" => "SO-00006", // You can generate this dynamically
                "Lines" => $lines,
                "AdditionalCharges" => $additionalCharges,
                "Memo" => $request->input('OrderMemo', null),
                "Status" => $request->input('OrderStatus', "DRAFT")
            ],
            "Quote" => [
                "Memo" => $request->input('QuoteMemo', null),
                "Status" => $request->input('QuoteStatus', "DRAFT"),
                "Prepayments" => $request->input('Prepayments', []),
                "Lines" => $lines,
                "AdditionalCharges" => $additionalCharges,
                "TotalBeforeTax" => array_sum(array_column($lines, 'Total')) + $request->input('shippingRate', 0),
                "Tax" => 0, // Calculate tax if applicable
                "Total" => array_sum(array_column($lines, 'Total')) + $request->input('shippingRate', 0)
            ],
            "Fulfilments" => [
                [
                    "FulfillmentNumber" => 1,
                    "LinkedInvoiceNumber" => "INV-00006",
                    "FulFilmentStatus" => $request->input('FulFilmentStatus', "NOT AVAILABLE"),
                    "Pick" => [
                        "Status" => $request->input('PickStatus', "NOT AVAILABLE"),
                        "Lines" => $lines
                    ],
                    "Pack" => [
                        "Status" => $request->input('PackStatus', "NOT AVAILABLE"),
                        "Lines" => $lines
                    ],
                    "Ship" => [
                        "Status" => $request->input('ShipStatus', "NOT AVAILABLE"),
                        "RequireBy" => $request->input('ShipBy'),
                        "ShippingAddress" => $request->input('ShippingAddress'),
                        "ShippingNotes" => $request->input('ShippingNotes'),
                        "Lines" => $lines
                    ]
                ]
            ],
            "Invoices" => [
                [
                    "InvoiceNumber" => "INV-00006",
                    "Memo" => $request->input('InvoiceMemo', null),
                    "Status" => $request->input('InvoiceStatus', "NOT AVAILABLE"),
                    "InvoiceDate" => $request->input('InvoiceDate', null),
                    "InvoiceDueDate" => $request->input('InvoiceDueDate', null),
                    "CurrencyConversionRate" => $request->input('CurrencyRate', 1.0),
                    "BillingAddressLine1" => $request->input('BillingAddress.Line1'),
                    "BillingAddressLine2" => $request->input('BillingAddress.Line2'),
                    "Lines" => $lines,
                    "AdditionalCharges" => $additionalCharges,
                    "Payments" => $request->input('Payments', []),
                    "TotalBeforeTax" => array_sum(array_column($lines, 'Total')) + $request->input('shippingRate', 0),
                    "Tax" => 0, // Calculate tax if applicable
                    "Total" => array_sum(array_column($lines, 'Total')) + $request->input('shippingRate', 0),
                    "Paid" => 0 // Update with actual paid amount if applicable
                ]
            ]
        ];
    
        $headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
            'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
        ];
    
        try {
            Log::info(__FILE__.__LINE__);
    
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $data
            ]);
    
            Log::info(__FILE__.__LINE__);
    
            $body = $response->getBody();
            $content = json_decode($body, true);
            Log::info(__FILE__.__LINE__);
    
            return response()->json($content);
        } catch (\Exception $e) {
            Log::error('Error creating sale order: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    

}
