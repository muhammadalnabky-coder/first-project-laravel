<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ratingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id'   => 'required|exists:bookings,id',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string',
        ];
    }
}
