<?php

namespace App\Classes;

class Person
{
    private Address $address;
    private Contact $contact; 

    public function __construct(
        Address $address,
        Contact $contact
    )
    {
        $this->address = $address;
        $this->contact = $contact;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}