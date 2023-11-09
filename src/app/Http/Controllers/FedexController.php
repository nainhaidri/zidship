<?php

namespace App\Http\Controllers;

use App\Http\Requests\Waybill\CancelWaybillRequest;
use App\Http\Requests\Waybill\CreateWaybillRequest;
use App\Http\Requests\Waybill\GetTrackingInfoRequest;
use App\Interfaces\CancellableCourierInterface;
use App\Services\FedexCourierService;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;

class FedexController extends Controller
{
    protected CancellableCourierInterface $courier;

    public function __construct(
        FedexCourierService $fedexCourier
    )
    {
        $this->courier = $fedexCourier;
    }

    public function createWaybill(CreateWaybillRequest $request): JsonResponse
    {
        return $this->courier->createWaybill($request->validated());
    }

    public function cancelWaybill(string $trackingNumber): JsonResponse
    {
        return $this->courier->cancelWaybill($trackingNumber);
    }

    public function getTrackingInfo(GetTrackingInfoRequest $request): JsonResponse
    {
        return $this->courier->getTrackingInfo($request->validated());
    }
}
