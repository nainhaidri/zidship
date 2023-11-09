<?php

namespace App\Enums;

class FedexPaymentTypes
{
    public static function getPaymentType(string $paymentType): string
    {
        switch ($paymentType) {
            case config('paymentTypes.sender'):
                return 'SENDER';
        }
    }
}