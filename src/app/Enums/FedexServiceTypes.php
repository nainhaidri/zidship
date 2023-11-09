<?php

namespace App\Enums;

class FedexServiceTypes
{
    public static function getServiceType(string $serviceType): string
    {
        switch ($serviceType) {
            case config('serviceTypes.priorityExpress'):
                return 'FEDEX_INTERNATIONAL_PRIORITY_EXPRESS';
            case config('serviceTypes.first'):
                return 'INTERNATIONAL_FIRST';
            case config('serviceTypes.priority'):
                return 'FEDEX_INTERNATIONAL_PRIORITY';
            case config('serviceTypes.economy'):
                return 'INTERNATIONAL_ECONOMY';
            case config('serviceTypes.ground'):
                return 'FEDEX_GROUND';
            case config('serviceTypes.mailService'):
                return 'FEDEX_CARGO_MAIL';
            case config('serviceTypes.premium'):
                return 'FEDEX_CARGO_INTERNATIONAL_PREMIUM';
            case config('serviceTypes.overnight'):
                return 'FIRST_OVERNIGHT';
            case config('serviceTypes.priorityOvernight'):
                return 'PRIORITY_OVERNIGHT';
            case config('serviceTypes.standardOvernight'):
                return 'STANDARD_OVERNIGHT';
            case config('serviceTypes.homeDelivery'): 
                return 'GROUND_HOME_DELIVERY';
        }
    }
}