<?php

namespace App\Services;

use Exception;
use App\Enums\FedexPackageTypes;
use App\Enums\FedexPaymentTypes;
use App\Enums\FedexPickupTypes;
use App\Enums\FedexServiceTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use App\Interfaces\CancellableCourierInterface;
use App\Repositories\Interfaces\CourierTokenRepositoryInterface;

class FedexCourierService implements CancellableCourierInterface
{
    public function __construct(
        private CourierTokenRepositoryInterface $repository
    )
    {
        
    }

    public function getToken(): string
    {
        $token = $this->repository->getCourierToken('fedex');

        if (now()->greaterThan($token->expiresAt)) {
            $response = \Http::asForm()
                            ->retry(3, 100, throw: false)
                            ->post(env('FEDEX_BASE_URL') . "/oauth/token", [
                                'grant_type'        => 'client_credentials',
                                'client_id'         => env('FEDEX_CLIENT_ID'),
                                'client_secret'     => env('FEDEX_CLIENT_SECRET')
                    ]);

            $responseBody = $response->json();

            if (!$response->successful()) {
                throw new AuthenticationException();
            }

            $token->token = \Crypt::encryptString($responseBody['access_token']);
            $token->expiresAt = now()->addSeconds($responseBody['expires_in']);
            $token->save();
        }

        return \Crypt::decryptString($token->token);
    }

    public function createWaybill(array $request): JsonResponse
    {
        $token          = $this->getToken();
        $recipients     = [];

        foreach ($request['recipients'] as $recipient) {
            $recipients[]                   = [
                'contact'                   => [
                    'personName'            => $recipient['name'],
                    'emailAddress'          => $recipient['email'] ?? null,
                    'phoneNumber'           => $recipient['phoneNumber'],
                    'companyName'           => $recipient['companyName'] ?? null
                ],
                'address'                   => [
                    'streetLines'           => [$recipient['address']['street']],
                    'city'                  => $recipient['address']['city'],
                    'stateOrProvinceCode'   => $recipient['address']['state'] ?? null,
                    'postalCode'            => $recipient['address']['postalCode'] ?? null,
                    'countryCode'           => $recipient['address']['countryCode'],
                    'residential'           => $recipient['address']['residential']
                ]
            ];
        }

        $requestBody    = [
            'requestedShipment'         => [
                'shipDatestamp'         => now()->parse($request['shipmentDatetime'])->format('Y-m-d'),
                'totalDeclaredValue'    => $request['totalDeclaredValue'] ?? null,
                'shipper'               => [
                    'address'           => [
                        'streetLines'   => [$request['shipper']['address']['street']],
                        'city'          => $request['shipper']['address']['city'],
                        'stateOrProvinceCode'   => $request['shipper']['address']['state'] ?? null,
                        'postalCode'    => $request['shipper']['address']['postalCode'] ?? null,
                        'countryCode'   => $request['shipper']['address']['countryCode'],
                        'residential'   => $request['shipper']['address']['residential']
                    ],
                    'contact'           => [
                        'personName'    => $request['shipper']['name'],
                        'emailAddress'  => $request['shipper']['email'] ?? null,
                        'phoneNumber'   => $request['shipper']['phoneNumber'],
                        'companyName'   => $request['shipper']['companyName'] ?? null
                    ],
                ],
                'origin'                => [
                    'address'           => [
                        'streetLines'   => [$request['origin']['address']['street']],
                        'city'          => $request['origin']['address']['city'],
                        'stateOrProvinceCode'   => $request['origin']['address']['state'] ?? null,
                        'postalCode'    => $request['origin']['address']['postalCode'] ?? null,
                        'countryCode'   => $request['origin']['address']['countryCode'],
                        'residential'   => $request['origin']['address']['residential']
                    ],
                    'contact'           => [
                        'personName'    => $request['origin']['name'],
                        'emailAddress'  => $request['origin']['email'] ?? null,
                        'phoneNumber'   => $request['origin']['phoneNumber'],
                        'companyName'   => $request['origin']['companyName'] ?? null
                    ],
                ],
                'recipients'            => $recipients,
                'pickupType'            => FedexPickupTypes::getPickupType($request['pickupType']),
                'serviceType'           => FedexServiceTypes::getServiceType($request['serviceType']),
                'packagingType'         => FedexPackageTypes::getPackageTypes($request['packageType']),
                'totalWeight'           => $request['totalWeight'] ?? null,
                
                'shippingChargesPayment'=> [
                    'paymentType'       => FedexPaymentTypes::getPaymentType($request['paymentType'])
                ],
                'labelSpecification'    => [
                    'labelStockType'    => 'PAPER_4X6',
                    'imageType'         => 'PDF'
                ],
                'requestedPackageLineItems' => $request['lineItems'],
            ],
            'labelResponseOptions'  => 'URL_ONLY',
            'accountNumber'         => [
                'value'             => env('FEDEX_ACCOUNT_NO')
            ],
        ];

        \Log::info(env('FEDEX_BASE_URL') . '/ship/v1/shipments');
        \Log::info(json_encode($requestBody));

        $response       = \Http::withToken($token)
                                ->retry(3, 100, throw: false)
                                ->post(env('FEDEX_BASE_URL') . '/ship/v1/shipments', $requestBody);

        \Log::info($response->body());

        if (!$response->successful()) {
            return response()->json([
                'message'        => 'Some error occurred. Please try again later'
            ], 500);
        }

        try {

            $shipments = [];
            foreach($response['output']['transactionShipments'] as $shipment) {
                $documents                  = [];
                foreach($shipment['pieceResponses'] as $pieceResponse ) {
                    $documents = array_merge($documents, $pieceResponse['packageDocuments']);
                }
                $shipments[] = [
                    'trackingNumber'        => $shipment['masterTrackingNumber'],
                    'shipmentDatetime'      => $shipment['shipDatestamp'],
                    'serviceName'           => $shipment['serviceName'],
                    'serviceCategory'       => $shipment['serviceCategory'],
                    'documents'             => $documents
                ];
            }
    
            return response()->json($shipments, 200);
        }
        catch(Exception $ex) {
            return response()->json([
                'message'        => 'Some error occurred. Please try again later'
            ], 500);
        }
    }

    public function printWaybillLabel(string $waybillId): JsonResponse
    {
        
    }

    public function getTrackingInfo(array $request): JsonResponse
    {
        $trackingNumbers = $request['trackingNumbers'];
        try {
            $token                          = $this->getToken();
            
            $trackingInfo                   = [];

            foreach($trackingNumbers as $trackingNo) {
                $trackingInfo[]             = [
                    'trackingNumberInfo'    => [
                        'trackingNumber'    => $trackingNo
                    ]
                ];
            }
            $requestBody            = [
                'includeDetailedScans'      => false,
                'trackingInfo'              => $trackingInfo
            ];

            \Log::info(env('FEDEX_BASE_URL') . '/track/v1/trackingnumbers');
            \Log::info(json_encode($requestBody));

            \Log::info($token);

            $response       = \Http::withToken($token)
                                ->retry(3, 100, throw: false)
                                ->post(env('FEDEX_BASE_URL') . '/track/v1/trackingnumbers', $requestBody);

            \Log::info($response->body());

            if (!$response->successful()) {
                return response()->json([
                    'message'        => 'Some error occurred. Please try again later'
                ], 500);
            }

            return response()->json($response->json());
        }
        catch(Exception $ex) {
            return response()->json([
                'message'        => 'Some error occurred. Please try again later'
            ], 500);
        }
    }

    public function cancelWaybill(string $trackingNumber): JsonResponse
    {
        try {
            $token                  = $this->getToken();
            $requestBody            = [
                'accountNumber'     => [
                    'value'         => env('FEDEX_ACCOUNT_NO')
                ],
                'trackingNumber'    => $trackingNumber
            ];
    
            \Log::info(env('FEDEX_BASE_URL') . '/ship/v1/shipments/cancel');
            \Log::info(json_encode($requestBody));
    
            $response       = \Http::withToken($token)
                                    ->retry(3, 100, throw: false)
                                    ->put(env('FEDEX_BASE_URL') . '/ship/v1/shipments/cancel', $requestBody);
    
            \Log::info($response->body());

            if (!$response->successful()) {
                return response()->json([
                    'message'           => 'Some error occurred. Please try again later'
                ], 500);
            }

            return response()->json([
                'trackingNumber'        => $trackingNumber,
                'cancelledShipment'     => true
            ], 200);
        }
        catch(Exception $ex) {
            return response()->json([
                'message'               => 'Some error occurred. Please try again later'
            ], 500);
        }
    }
}