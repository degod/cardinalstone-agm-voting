<?php

namespace App\Http\Requests;

use App\Enums\AgmStatuses;
use Illuminate\Foundation\Http\FormRequest;

class AgmEditRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'sometimes|required|date',
            'voting_start_time' => 'sometimes|required|date|after_or_equal:meeting_date',
            'voting_end_time' => 'sometimes|required|date|after:voting_start_time',
            'status' => 'required|in:' . AgmStatuses::ACTIVE . ',' . AgmStatuses::CANCELLED . ',' . AgmStatuses::DRAFT . ',' . AgmStatuses::CLOSED,
        ];
    }
}