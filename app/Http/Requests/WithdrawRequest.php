<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            // 'account_number' => ['required', 'string', 'min:10'],
            'amount' => ['required', 'numeric', ],
            'pin' => ['required', 'string', 'min:4'],
            'description' => ['required', 'string', 'min:5'],
        ];
    }
}
