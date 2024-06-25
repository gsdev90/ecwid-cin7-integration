<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Helpers\SkuTransformer; // Import the helper class
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Cin7Controller;

class EcwidController extends Controller
{
    protected $client;
    protected $cin7Controller;

    public function __construct()
    {
        $this->client = new Client();
    }

    // public function __construct(Client $client, Cin7Controller $cin7Controller)
    // {
    //     $this->client = $client;
    //     $this->cin7Controller = $cin7Controller;
    // }
    

    public function fetchOrders($type = 'all')
    {
        $storeId = env('ECWID_STORE_ID');
        $apiToken = env('ECWID_API_TOKEN');

        $url = "https://app.ecwid.com/api/v3/{$storeId}/orders";
        $queryParams = [
            'count' => 100,
            'paymentStatus' => 'PAID',
            'fulfillmentStatus' => 'AWAITING_PROCESSING,PROCESSING,SHIPPED'
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

            $extractedData = array_map(function($order) {
                return [
                    'orderNumber' => $order['id'],
                    'total' => $order['total'],
                    'orderComments' => isset($order['orderComments']) ? $order['orderComments'] : 'NoCustomerComments',
                    'createDate' => $order['createDate'],
                    'updateDate' => $order['updateDate'],
                    'items' => array_map(function($item) {

                        if (($item['name'] == "Test tees JIC") || ($item['name'] == "Test tees BSPT")) {
                            $item['new_sku'] = $item['sku'];
                        } else {
                            // modify sku using helper class
                             $item['new_sku'] = SkuTransformer::transform($item['selectedOptions'] ?? []);
                        }
                        return [
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'sku' => $item['sku'],
                            'new-sku' => $item['new_sku'],
                            'price' => $item['price'],
                            'nameTranslated' => isset($item['nameTranslated']['en']) ? $item['nameTranslated']['en'] : null,
                            'selectedOptions' => isset($item['selectedOptions']) ? array_map(function($option) {
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
                        'street' =>  $order['billingPerson']['street'],
                        'city' =>  $order['billingPerson']['city'],
                        'stateOrProvinceName' => $order['billingPerson']['stateOrProvinceName'],
                        'postalCode' => $order['billingPerson']['postalCode'],
                        'country' => $order['billingPerson']['countryName']
                    ] : null,
                    'payment' =>[
                        'paymentMethod' => $this->getPaymentMethod($order['paymentMethod']),
                        'paymentId' => isset($order['externalTransactionId']) ? $this->getPaymentMethod($order['externalTransactionId']) : '',
                    ],
                    'additionalInformation' => [
                        'paymentMethod' => $this->getPaymentMethod($order['ipAddress']),
                    ]
                ];
            }, $jsonData['items']);

            Log::info('Orders processed successfully', ['extractedData' => $extractedData]);


            // Call createCustomerForCin7 method with the first order
            // if (!empty($extractedData)) {
            //     $this->cin7Controller->createCustomerForCin7($extractedData[0]);
            // }

            // if ($type == 'first') {
            //     return response()->json($extractedData[0], 200);
            // } elseif ($type == 'last') {
            //     return response()->json(end($extractedData), 200);
            // }

            // foreach ($extractedData as $order) {
            //     $this->cin7Controller->createCustomerInternal($order);
            // }

            return response()->json($extractedData, 200, [], JSON_PRETTY_PRINT);
            // Call pushToCin7 method with the extracted orders
            // return $this->pushToCin7(new Request(['orders' => $extractedData]));

        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch orders', 'message' => $e->getMessage()], 500);
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


    // To get Product ID
    public function fetchProductIds()
    {
        $storeId = env('ECWID_STORE_ID');
        $apiToken = env('ECWID_API_TOKEN');

        $url = "https://app.ecwid.com/api/v3/{$storeId}/products";
        $queryParams = [
            'limit' => 500, // Adjust the limit as needed
            'offset' => 0
        ];

        try {
            Log::info('Fetching product IDs from Ecwid', ['url' => $url, 'queryParams' => $queryParams]);

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$apiToken}",
                ],
                'query' => $queryParams
            ]);

            $jsonData = json_decode($response->getBody()->getContents(), true);
            Log::info('Product IDs fetched successfully', ['products' => $jsonData]);

            $extractedData = array_map(function($product) {
                return [
                    'productId' => $product['id'],
                    'name' => $product['name'],
                    'sku' => $product['sku']
                ];
            }, $jsonData['items']);

            Log::info('Product IDs processed successfully', ['extractedData' => $extractedData]);

            return response()->json($extractedData, 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            Log::error('Error fetching product IDs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product IDs', 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchProductVariations()
    {
        $productId = "129101039"; //129101039  138836880
        $storeId = env('ECWID_STORE_ID');
        $apiToken = env('ECWID_API_TOKEN');

        $url = "https://app.ecwid.com/api/v3/{$storeId}/products/{$productId}/combinations";

        try {
            Log::info('Fetching product variations from Ecwid', ['url' => $url]);

            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$apiToken}",
                ]
            ]);

            $jsonData = json_decode($response->getBody()->getContents(), true);
            Log::info('Product variations fetched successfully', ['variations' => $jsonData]);

            if (!isset($jsonData['combinations'])) {
                Log::warning('No product variations found for the given product ID', ['productId' => $productId]);
                return response()->json(['message' => 'No product variations found'], 200);
            }

            $extractedData = array_map(function($variation) {
                return [
                    'combinationId' => $variation['id'],
                    'sku' => $variation['sku'],
                    'quantity' => $variation['quantity'],
                    'price' => $variation['price'],
                    'options' => array_map(function($option) {
                        return [
                            'name' => $option['name'],
                            'value' => $option['value'],
                        ];
                    }, $variation['options']),
                ];
            }, $jsonData['combinations']);

            Log::info('Product variations processed successfully', ['extractedData' => $extractedData]);

            return response()->json($extractedData, 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            Log::error('Error fetching product variations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product variations', 'message' => $e->getMessage()], 500);
        }
    }





    // public function pushToCin7(Request $request)
    // {
    //     $cin7AccountId = env('CIN7_ACCOUNT_ID');
    //     $cin7ApiKey = env('CIN7_API_KEY');
    //     $cin7ApiUrl = env('CIN7_API_KEY');
    //     $orders = $request->input('orders');
    
    //     foreach ($orders as $order) {
    //         $orderData = [
    //             'Reference' => $order['orderNumber'],
    //             'MemberId' => 12345, // Adjust this to dynamically fetch the member ID
    //             'FirstName' => $order['shippingPerson']['fullName'],
    //             'LastName' => '',
    //             'Email' => $order['shippingPerson']['email'],
    //             'Phone' => $order['shippingPerson']['phone'],
    //             'DeliveryAddress1' => $order['shippingPerson']['street'],
    //             'DeliveryCity' => $order['shippingPerson']['city'],
    //             'DeliveryState' => $order['shippingPerson']['stateOrProvinceName'],
    //             'DeliveryPostalCode' => $order['shippingPerson']['postalCode'],
    //             'DeliveryCountry' => $order['shippingPerson']['countryName'],
    //             'BillingAddress1' => $order['billingPerson']['street'],
    //             'BillingCity' => $order['billingPerson']['city'],
    //             'BillingState' => $order['billingPerson']['stateOrProvinceName'],
    //             'BillingPostalCode' => $order['billingPerson']['postalCode'],
    //             'BillingCountry' => $order['billingPerson']['country'],
    //             'LineItems' => array_map(function($item) {
    //                 return [
    //                     'ProductCode' => $item['new-sku'],
    //                     'Quantity' => $item['quantity'],
    //                     'UnitPrice' => $item['price'],
    //                 ];
    //             }, $order['items']),
    //             'Total' => $order['total'],
    //             'CurrencyCode' => 'USD'
    //         ];
    
    //         try {
    //             $response = $this->client->request('POST', $cin7ApiUrl, [
    //                 'headers' => [
    //                     'Content-Type' => 'application/json',
    //                     'Authorization' => "Bearer {$cin7ApiKey}",
    //                 ],
    //                 'body' => json_encode($orderData)
    //             ]);
    
    //             $responseBody = json_decode($response->getBody()->getContents(), true);
    
    //             if (isset($responseBody['error'])) {
    //                 return response()->json(['error' => 'Failed to push order to Cin7', 'message' => $responseBody['error']], 500);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Failed to push order to Cin7', 'message' => $e->getMessage()], 500);
    //         }
    //     }
    
    //     return response()->json(['success' => 'Orders pushed to Cin7 successfully'], 200);
    // }

    // private function findOrCreateCustomer($shippingPerson, $apiKey)
    // {
    //     // Search for the customer
    //     $searchUrl = 'https://api.cin7.com/api/v1/Contacts';
    //     $queryParams = [
    //         'where' => "EmailAddress='{$shippingPerson['email']}' OR Phone='{$shippingPerson['phone']}'"
    //     ];
    
    //     try {
    //         $response = $this->client->request('GET', $searchUrl, [
    //             'headers' => [
    //                 'Accept' => 'application/json',
    //                 'Authorization' => "Bearer {$apiKey}",
    //             ],
    //             'query' => $queryParams
    //         ]);
    
    //         $responseBody = json_decode($response->getBody()->getContents(), true);
    
    //         if (!empty($responseBody['Items'])) {
    //             return $responseBody['Items'][0]['ContactID']; // Return existing customer ID
    //         }
    //     } catch (\Exception $e) {
    //         // Handle errors
    //     }
    
    //     // If not found, create a new customer
    //     $createUrl = 'https://api.cin7.com/api/v1/Contacts';
    //     $customerData = [
    //         'FirstName' => $shippingPerson['fullName'],
    //         'Email' => $shippingPerson['email'],
    //         'Phone' => $shippingPerson['phone'],
    //         'Address' => $shippingPerson['street'],
    //         'City' => $shippingPerson['city'],
    //         'State' => $shippingPerson['stateOrProvinceName'],
    //         'Postcode' => $shippingPerson['postalCode'],
    //         'Country' => $shippingPerson['countryName']
    //     ];
    
    //     try {
    //         $response = $this->client->request('POST', $createUrl, [
    //             'headers' => [
    //                 'Content-Type' => 'application/json',
    //                 'Authorization' => "Bearer {$apiKey}",
    //             ],
    //             'body' => json_encode($customerData)
    //         ]);
    
    //         $responseBody = json_decode($response->getBody()->getContents(), true);
    
    //         return $responseBody['ContactID']; // Return new customer ID
    //     } catch (\Exception $e) {
    //         return null; // Handle errors
    //     }
    // }

    // public function createCustomer()
    // {
    //     Log::info("name: " . "test");
    //     Log::info("value : " ."test");

    //     $client = new Client();
    //     $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';
        
    //     $data = [
    //             "Name": "Australia - Hydraulic Services test ",
    //             "Currency": "AUD",
    //             "PaymentTerm": "30 days",
    //             "Discount": 0,
    //             "TaxRule": "GST on Income",
    //             "Carrier": "DEFAULT Carrier",
    //             "SalesRepresentative": null,
    //             "Location": "Main Warehouse",
    //             "Comments": null,
    //             "AccountReceivable": "610",
    //             "RevenueAccount": "200",
    //             "PriceTier": "Tier 1",
    //             "TaxNumber": null,
    //             "AttributeSet": null,
    //             "Tags": null,
    //             "Status": "Active",
    //             "IsOnCreditHold": true,
    //             "Addresses": [
    //                 {
    //                     "Line1": "L6, Southbank House",
    //                     "Line2": "15 Gallery Ave",
    //                     "City": "Melbourne",
    //                     "State": "VIC",
    //                     "Postcode": "3131",
    //                     "Country": "Australia",
    //                     "Type": "Business",
    //                     "DefaultForType": true
    //                 },
    //                 {
    //                     "Line1": "L5, Southbank House",
    //                     "Line2": "15 Gallery Ave",
    //                     "City": "Melbourne",
    //                     "State": "VIC",
    //                     "Postcode": "3131",
    //                     "Country": "Australia",
    //                     "Type": "Billing",
    //                     "DefaultForType": true
    //                 }
    //             ],
    //             "Contacts": [
    //                 {
    //                     "Name": "Sheree Test",
    //                     "JobTitle": null,
    //                     "Phone": "0800 4389390",
    //                     "MobilePhone": null,
    //                     "Fax": "03 4389379",
    //                     "Email": "account.test@diisr.govt",
    //                     "Website": null,
    //                     "Default": true,
    //                     "Comment": null,
    //                     "IncludeInEmail": false
    //                 }
    //             ]
    //     ];
        
    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'api-auth-accountid' => '1a62ed7e-8c8d-4a59-8bcc-e074cd3f82dd',
    //         'api-auth-applicationkey' => '0495fe34-f7c1-ae3c-8b67-0ef00e8436e4'
    //     ];
        
    //     try {
    //         // Implementing a delay of 0.34 seconds (approximately 3 requests per second)
    //         // usleep(340000); 

    //         log::info(__FILE__.__LINE__);
            
    //         $response = $client->post($url, [
    //             'headers' => $headers,
    //             'json' => $data
    //         ]);

    //         log::info(__FILE__.__LINE__);
            
    //         $body = $response->getBody();
    //         $content = json_decode($body, true);
    //         log::info(__FILE__.__LINE__);
            
    //         return response()->json($content);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function createSalesOrder()
    // {
    //     $client = new Client();
    //     $url = 'https://api.cin7.com/api/v1/SalesOrders';
        
    //     $data = [
    //         [
    //             "memberId" => 41,
    //             "lineItems" => [
    //                 [
    //                     "code" => "ProductZ",
    //                     "qty" => 1.0
    //                 ]
    //             ]
    //         ]
    //     ];
        
    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'api-auth-accountid' => 'your_account_id',
    //         'api-auth-applicationkey' => 'your_application_key'
    //     ];
        
    //     try {
    //         // Implementing a delay of 0.34 seconds (approximately 3 requests per second)
    //         usleep(340000); 
            
    //         $response = $client->post($url, [
    //             'headers' => $headers,
    //             'json' => $data
    //         ]);
            
    //         $body = $response->getBody();
    //         $content = json_decode($body, true);
            
    //         return response()->json($content);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    public function fetchOrder($type = 'all')
   {
        try {
            Log::info('fetchOrders method called');
            // Your existing code
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch orders', 'message' => $e->getMessage()], 500);
        }
   }
}
