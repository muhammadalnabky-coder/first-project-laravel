<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateApartmentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
             'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'city' => 'sometimes|string',
            'Governorate'=> 'sometimes|string',
            'address' => 'sometimes|string',
            'price_per_day' => 'sometimes|numeric',
            'rooms' => 'sometimes|integer',
            'bathrooms' => 'sometimes|integer',
            'area' => 'sometimes|numeric',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ];
    }
}
