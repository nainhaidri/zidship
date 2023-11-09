<?php

namespace App\Classes;

class Contact
{
    private string $personName, $emailAddress, $phoneExtension, $phoneNumber, $companyName;

    public function __construct(
        string $personName = null,
        string $emailAddress = null,
        string $phoneExtension = null,
        string $phoneNumber,
        string $companyName = null
    )
    {
        $this->personName = $personName;
        $this->emailAddress = $emailAddress;
        $this->phoneExtension = $phoneExtension;
        $this->phoneNumber = $phoneNumber;
        $this->companyName = $companyName;
    }

    public function getPersonName(): ?string
    {
        return $this->personName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function getPhoneExtension(): ?string
    {
        return $this->phoneExtension;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}