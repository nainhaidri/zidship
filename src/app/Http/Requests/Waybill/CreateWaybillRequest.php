<?php

namespace App\Http\Requests\Waybill;

use Illuminate\Foundation\Http\FormRequest;

class CreateWaybillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipper.name'                          => 'required|max:50',
            'shipper.email'                         => 'nullable|email',
            'shipper.phoneNumber'                   => 'required|max:15',
            'shipper.companyName'                   => 'nullable|max:80',
            'shipper.address.street'                => 'required|max:80',
            'shipper.address.city'                  => 'required|max:35',
            'shipper.address.state'                 => 'nullable|max:50',
            'shipper.address.postalCode'            => 'nullable|max:15',
            'shipper.address.countryCode'           => 'required|min:2|max:2',
            'shipper.address.residential'           => 'required|boolean',
            'recipients'                            => 'required|array',
            'recipients.*.name'                     => 'required|max:50',
            'recipients.*.email'                    => 'nullable|email',
            'recipients.*.phoneNumber'              => 'required|max:15',
            'recipients.*.companyName'              => 'nullable|max:80',
            'recipients.*.address.street'           => 'required|max:80',
            'recipients.*.address.city'             => 'required|max:35',
            'recipients.*.address.state'            => 'nullable|max:50',
            'recipients.*.address.postalCode'       => 'nullable|max:15',
            'recipients.*.address.countryCode'      => 'required|min:2|max:2',
            'recipients.*.address.residential'      => 'required|boolean',
            'shipmentDatetime'                      => 'required|date',
            'serviceType'                           => 'required|in:'. implode(",", array_values(config('serviceTypes'))),
            'packageType'                           => 'required|in:'. implode(",", array_values(config('packageTypes'))),
            'pickupType'                            => 'required|in:'. implode(",", array_values(config('pickupTypes'))),
            'totalWeight'                           => 'nullable|numeric',
            'totalDeclaredValue'                    => 'nullable',
            'totalDeclaredValue.amount'             => 'nullable|numeric|min:0.01',
            'totalDeclaredValue.currency'           => 'nullable|in:'. implode(',', config('currencies')),
            'origin'                                => 'required',
            'origin.name'                           => 'required|max:50',
            'origin.phoneNumber'                    => 'required|max:15',
            'origin.companyName'                    => 'nullable|max:80',
            'origin.address.street'                 => 'required|max:80',
            'origin.address.city'                   => 'required|max:35',
            'origin.address.state'                  => 'nullable|max:50',
            'origin.address.postalCode'             => 'nullable|max:15',
            'origin.address.countryCode'            => 'required|min:2|max:2',
            'origin.address.residential'            => 'required|boolean',
            'paymentType'                           => 'required|in:'. implode(",", array_values(config('paymentTypes'))),
            'lineItems'                             => 'required|array',
            'lineItems.*.value'                     => 'nullable|numeric',
            'lineItems.*.currency'                  => 'nullable|required_with:lineItems.*.value|in:'. implode(',', config('currencies')),
            'lineItems.*.weight.units'              => 'required|in:' . implode(',', config('weightUnits')),
            'lineItems.*.weight.value'              => 'required|numeric',
            'lineItems.*.dimensions'                => 'nullable|required_without:lineItems.*.weight|array',
            'lineItems.*.dimensions.unit'           => 'nullable|required_without:lineItems.*.weight|in:' . implode(',', config('weightUnits')),
            'lineItems.*.dimensions.length'         => 'nullable|required_without:lineItems.*.weight|numeric',
            'lineItems.*.dimensions.width'          => 'nullable|required_without:lineItems.*.weight|numeric',
            'lineItems.*.dimensions.height'         => 'nullable|required_without:lineItems.*.weight|numeric',
            'lineItems.*.description'               => 'nullable|max:255',
        ];
    }
}
