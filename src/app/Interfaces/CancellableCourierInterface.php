<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface CancellableCourierInterface extends CourierInterface
{
    public function cancelWaybill(string $trackingNumber): JsonResponse;
}