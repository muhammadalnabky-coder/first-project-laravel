<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date',
            'apartment_id' => 'nullable|exists:apartments,id',
            'notes'        => 'nullable|string|max:500'
        ];
    }

}
