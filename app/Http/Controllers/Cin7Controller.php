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
        // Log::info('Create Customer Request:', $order);

        return $order;

        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';

        $data = [
            "Name" => "GOLD Coast hydraulic services test",
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

    public function createCustomer(Request $request)
    {
        
        Log::info('Create Customer Request:', $request->all());

        $client = new Client();
        $url = 'https://inventory.dearsystems.com/ExternalApi/v2/customer';

        $data = [
            "Name" => "NewZealand hydraulic services",
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
                    "Line1" => "L6, Southbank House",
                    "Line2" => "15 Gallery Ave",
                    "City" => "Melbourne",
                    "State" => "VIC",
                    "Postcode" => "3131",
                    "Country" => "Australia",
                    "Type" => "Business",
                    "DefaultForType" => true
                ],
                [
                    "Line1" => "L5, Southbank House",
                    "Line2" => "15 Gallery Ave",
                    "City" => "Melbourne",
                    "State" => "VIC",
                    "Postcode" => "3131",
                    "Country" => "Australia",
                    "Type" => "Billing",
                    "DefaultForType" => true
                ]
            ],
            "Contacts" => [
                [
                    "Name" => "Test coomera",
                    "JobTitle" => null,
                    "Phone" => "04504389390",
                    "MobilePhone" => null,
                    "Fax" => "0384389379",
                    "Email" => "accountt23@diisr.govt",
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
}
