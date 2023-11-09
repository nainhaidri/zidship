<?php

namespace App\Repositories;

use App\Models\CourierToken;
use App\Repositories\Interfaces\CourierTokenRepositoryInterface;

class CourierTokenRepository implements CourierTokenRepositoryInterface
{
    public function __construct(
        private CourierToken $model
    )
    {
        
    }

    public function getCourierToken(string $courier): mixed
    {
        return $this->model->where('courier', $courier)->first();
    }
}