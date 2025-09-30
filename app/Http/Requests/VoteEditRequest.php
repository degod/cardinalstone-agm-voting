<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vote_value' => 'sometimes|required|in:yes,no,for,against,abstain',
            'votes_cast' => 'sometimes|required|integer|min:1',
        ];
    }
}
