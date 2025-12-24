<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class searchApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Governorate'=> 'nullable|string',
            'city'   => 'nullable|string',
            'rooms'  => 'nullable|integer',
            'area'   => 'nullable|numeric',
            'address'=> 'nullable|string',
            'min'    => 'nullable|numeric',
            'max'    => 'nullable|numeric',
        ];
    }
}
