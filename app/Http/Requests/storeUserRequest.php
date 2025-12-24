<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeUserRequest extends FormRequest
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
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'birth_date'   => 'required|date',
            'profile_image'   => 'nullable',
            'profile_image.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'id_image'   => 'nullable',
            'id_image.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'gender'       => 'sometimes|string',
            'phone'        => 'required|string|unique:users|max:15|min:10',
            'password'     => 'required|string|min:6',
        ];
    }
}
