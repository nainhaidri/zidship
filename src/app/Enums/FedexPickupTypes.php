<?php

namespace App\Enums;

class FedexPickupTypes
{
    public static function getPickupType(string $pickupType): string
    {
        switch ($pickupType) {
            case config('pickupTypes.contactToPickup'):
                return 'CONTACT_FEDEX_TO_SCHEDULE';
            case config('pickupTypes.dropOffAtOffice'):
                return 'DROPOFF_AT_FEDEX_LOCATION';
            case config('pickupTypes.scheduledPickup'):
                return 'USE_SCHEDULED_PICKUP';
        }
    }
}