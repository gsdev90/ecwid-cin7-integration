<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
                    'orderComments' => $order['orderComments'],
                    'createDate' => $order['createDate'],
                    'updateDate' => $order['updateDate'],
                    'items' => array_map(function($item) {
                        return [
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'sku' => $item['sku'],
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
        } catch (\Exception $e) {
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
}
