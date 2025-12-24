<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateProfileRequest extends FormRequest
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
            'first_name'     => 'nullable|string',
            'last_name'      => 'nullable|string',
            'birth_date'     => 'nullable|date',
            'profile_image'   => 'nullable',
            'profile_image.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'id_image'   => 'nullable',
            'id_image.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}
