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
            'description' => 'nullable|string',
            
            'items' => 'required|array|min:1',
            'items.*.item_number' => 'required|integer|min:1',
            'items.*.title' => 'required|string|max:500',
            'items.*.item_type' => 'required|in:' . implode(',', array_keys(ItemTypes::asKeyValue())),
            'items.*.voting_type' => 'required|in:' . implode(',', array_keys(VoteTypes::asKeyValue())),
        ];
    }
}