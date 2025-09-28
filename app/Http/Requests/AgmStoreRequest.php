<?php

namespace App\Http\Requests;

use App\Enums\AgmStatuses;
use Illuminate\Foundation\Http\FormRequest;

class AgmStoreRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'required|date',
            'voting_start_time' => 'required|date|after_or_equal:meeting_date',
            'voting_end_time' => 'required|date|after:voting_start_time',
            'status' => 'required|in:' . AgmStatuses::ACTIVE . ',' . AgmStatuses::CANCELLED . ',' . AgmStatuses::DRAFT . ',' . AgmStatuses::CLOSED,
        ];
    }
}
