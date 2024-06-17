<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Helpers\SkuTransformer; // Import the helper class

class EcwidController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchOrders()
    {
        $storeId = env('ECWID_STORE_ID');
        $apiToken = env('ECWID_API_TOKEN');

        $url = "https://app.ecwid.com/api/v3/{$storeId}/orders";
        $queryParams = [
            'count' => 100,
            'paymentStatus' => 'PAID',
            'fulfillmentStatus' => 'AWAITING_PROCESSING,PROCESSING'
        ];

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$apiToken}",
                ],
                'query' => $queryParams
            ]);

            $jsonData = json_decode($response->getBody()->getContents(), true);

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
                        'paymentId' => $this->getPaymentMethod($order['externalTransactionId']),
                    ],
                    'additionalInformation' => [
                        'paymentMethod' => $this->getPaymentMethod($order['ipAddress']),
                    ]
                ];
            }, $jsonData['items']);

            return response()->json($extractedData, 200, [], JSON_PRETTY_PRINT);
            // Call pushToCin7 method with the extracted orders
            // return $this->pushToCin7(new Request(['orders' => $extractedData]));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch orders', 'message' => $e->getMessage()], 500);
        }
    }

    public function pushToCin7(Request $request)
    {
        $cin7AccountId = env('CIN7_ACCOUNT_ID');
        $cin7ApiKey = env('CIN7_API_KEY');
        $cin7ApiUrl = env('CIN7_API_KEY');
        $orders = $request->input('orders');
    
        foreach ($orders as $order) {
            $orderData = [
                'Reference' => $order['orderNumber'],
                'MemberId' => 12345, // Adjust this to dynamically fetch the member ID
                'FirstName' => $order['shippingPerson']['fullName'],
                'LastName' => '',
                'Email' => $order['shippingPerson']['email'],
                'Phone' => $order['shippingPerson']['phone'],
                'DeliveryAddress1' => $order['shippingPerson']['street'],
                'DeliveryCity' => $order['shippingPerson']['city'],
                'DeliveryState' => $order['shippingPerson']['stateOrProvinceName'],
                'DeliveryPostalCode' => $order['shippingPerson']['postalCode'],
                'DeliveryCountry' => $order['shippingPerson']['countryName'],
                'BillingAddress1' => $order['billingPerson']['street'],
                'BillingCity' => $order['billingPerson']['city'],
                'BillingState' => $order['billingPerson']['stateOrProvinceName'],
                'BillingPostalCode' => $order['billingPerson']['postalCode'],
                'BillingCountry' => $order['billingPerson']['country'],
                'LineItems' => array_map(function($item) {
                    return [
                        'ProductCode' => $item['new-sku'],
                        'Quantity' => $item['quantity'],
                        'UnitPrice' => $item['price'],
                    ];
                }, $order['items']),
                'Total' => $order['total'],
                'CurrencyCode' => 'USD'
            ];
    
            try {
                $response = $this->client->request('POST', $cin7ApiUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => "Bearer {$cin7ApiKey}",
                    ],
                    'body' => json_encode($orderData)
                ]);
    
                $responseBody = json_decode($response->getBody()->getContents(), true);
    
                if (isset($responseBody['error'])) {
                    return response()->json(['error' => 'Failed to push order to Cin7', 'message' => $responseBody['error']], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to push order to Cin7', 'message' => $e->getMessage()], 500);
            }
        }
    
        return response()->json(['success' => 'Orders pushed to Cin7 successfully'], 200);
    }



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
      

  // these function 
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
}
