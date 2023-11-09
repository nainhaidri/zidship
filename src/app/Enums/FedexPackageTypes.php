<?php

namespace App\Enums;

class FedexPackageTypes
{
    public static function getPackageTypes(string $packageType): string
    {
        switch ($packageType) {
            case config('packageTypes.customerOwnPackage'):
                return 'YOUR_PACKAGING';
            case config('packageTypes.letter'):
                return 'FEDEX_ENVELOPE';
            case config('packageTypes.smallBox'):
                return 'FEDEX_SMALL_BOX';
            case config('packageTypes.mediumBox'):
                return 'FEDEX_MEDIUM_BOX';
            case config('packageTypes.largeBox'):
                return 'FEDEX_LARGE_BOX';
            case config('packageTypes.extraLargeBox'):
                return 'FEDEX_EXTRA_LARGE_BOX';
            case config('packageTypes.tenKGBox'):
                return 'FEDEX_10KG_BOX';
            case config('packageTypes.twentyFiveKGBox'):
                return 'FEDEX_25KG_BOX';
            case config('packageTypes.tube'):
                return 'FEDEX_TUBE';
        }
    }
}