<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'city' => 'required|string',
            'Governorate'=> 'required|string',
            'address' => 'required|string',
            'price_per_day' => 'required|numeric',
            'rooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'area' => 'required|numeric',
            'image_url.*'   => 'nullable',
            'image_url.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ];
    }
}
