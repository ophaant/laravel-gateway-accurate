<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|integer',
            'account_number' => 'required|integer',
            'account_name' => 'required|string|max:50',
            'category_bank_id' => 'required|uuid',
            'account_type_id' => 'required|uuid',
        ];

    }
}
