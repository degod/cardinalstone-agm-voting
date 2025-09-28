<?php

namespace App\Http\Requests;

use App\Enums\ItemTypes;
use App\Enums\VoteTypes;
use Illuminate\Foundation\Http\FormRequest;

class AgendaStoreRequest extends FormRequest
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
            'agm_id' => 'required|exists:agms,id',
            'item_number' => 'required|integer|min:1',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'item_type' => 'required|in:' . implode(',', ItemTypes::asKeyValue()),
            'voting_type' => 'required|in:' . implode(',', VoteTypes::asKeyValue()),
            'is_active' => 'boolean',
        ];
    }
}
