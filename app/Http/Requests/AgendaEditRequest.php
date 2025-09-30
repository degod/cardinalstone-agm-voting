<?php

namespace App\Http\Requests;

use App\Enums\ItemTypes;
use App\Enums\VoteTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaEditRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'integer', 'exists:agendas,id'],
            'items.*.item_number' => ['required', 'integer', 'min:1'],
            'items.*.title' => ['required', 'string', 'max:500'],
            'items.*.item_type' => ['required', Rule::in(array_keys(ItemTypes::asKeyValue()))],
            'items.*.voting_type' => ['required', Rule::in(array_keys(VoteTypes::asKeyValue()))],
        ];
    }
}