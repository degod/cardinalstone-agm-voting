<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteStoreRequest extends FormRequest
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
            'vote_value' => 'required|array',
            'vote_value.*' => 'required|in:yes,no,for,against,abstain',
            'votes_cast' => 'required|integer|min:1',
        ];
    }
}
