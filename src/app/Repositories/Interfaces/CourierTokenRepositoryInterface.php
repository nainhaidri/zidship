<?php

namespace App\Repositories\Interfaces;

interface CourierTokenRepositoryInterface
{
    public function getCourierToken(string $courier): mixed;
}