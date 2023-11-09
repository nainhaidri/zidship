<?php

namespace App\Http\Requests\Waybill;

use Illuminate\Foundation\Http\FormRequest;

class GetTrackingInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trackingNumbers'       => 'required|array',
            'trackingNumbers.*'     => 'required|integer'
        ];
    }
}
