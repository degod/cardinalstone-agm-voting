<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareholderEditRequest extends FormRequest
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
            'shares_owned' => 'sometimes|required|integer|min:1',
            'share_certificate_number' => 'nullable|string|max:255',
            'acquired_date' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
