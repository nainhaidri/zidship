<?php

namespace App\Classes;

use DateTime;
use App\Enums\PickupType;
use App\Enums\ServiceType;

class Shipment
{
    private string $id;
    private DateTime $shipDatetime;
    private float $value;
    private Person $shipper;
    private array $recipients;
    private PickupType $pickupType;
    private ServiceType $serviceType;
    private ?Person $origin;
    private int $packageCount;

    public function setShipDatetime(DateTime $shipDatetime): void
    {
        $this->shipDatetime = $shipDatetime;
    }

    public function getShipDatetime(): DateTime
    {
        return $this->shipDatetime;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setShipper(Person $shipper): void
    {
        $this->shipper = $shipper;
    }

    public function getShipper(): Person
    {
        return $this->shipper;
    }

    public function setRecipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setOrigin(Person $origin): void
    {
        $this->origin = $origin;
    }

    public function getOrigin(): Person
    {
        return $this->origin;
    }

    public function setPackageCount(int $packageCount): void
    {
        $this->packageCount = $packageCount;
    }

    public function getPackageCount(): int
    {
        return $this->packageCount;
    }

    public function setServiceType(ServiceType $serviceType): void
    {
        $this->serviceType = $serviceType;
    }

    public function getServiceType(): ServiceType
    {
        return $this->serviceType;
    }

    public function setPickupType(PickupType $pickupType): void
    {
        $this->pickupType = $pickupType;
    }

    public function getPickupType(): PickupType
    {
        return $this->pickupType;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}