<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface CourierInterface
{
    public function getToken(): string;

    public function createWaybill(array $request): JsonResponse;

    public function printWaybillLabel(string $waybillId): JsonResponse;

    public function getTrackingInfo(array $request): JsonResponse;
}