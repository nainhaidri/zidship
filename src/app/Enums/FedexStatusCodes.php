<?php

namespace App\Enums;

class FedexStatusCodes
{
    public static function getStatus(string $statusCode): string
    {
        return config('statusCodes.' . $statusCode);
    }
}