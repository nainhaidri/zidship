<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'courier',
        'token',
        'expiresAt'
    ];
}
