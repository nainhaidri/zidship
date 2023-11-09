<?php

namespace App\Classes;

class Address
{
    private string $streetAddress, $city, $state, $postalCode, $countryCode;
    private bool $isResidential;

    public function __construct(
        string $streetAddress,
        string $city,
        string $state = null,
        string $postalCode = null,
        string $countryCode,
        bool $isResidential
    )
    {
        $this->streetAddress = $streetAddress;
        $this->city          = $city;
        $this->state         = $state;
        $this->postalCode    = $postalCode;
        $this->countryCode   = $countryCode;
        $this->isResidential = $isResidential;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getIsResidential(): bool
    {
        return $this->isResidential;
    }
}