<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        if ($this->method() == 'GET') {
            return [
                'code_database' => 'required|integer',
                'page' => 'integer|nullable|min:1',
                'keywords' => 'string|nullable',
            ];
        }
        if ($this->method() == 'POST') {
            return [
                'code_database' => 'required|integer',
                'name' => 'required|string',
                'customerNo' => 'required|string',
                'branchName' => 'required|string',
                'categoryName' => 'required|string',
                'salesmanNumber' => 'required|string',
            ];
        }
    }

    public function messages()
    {
        return [
            'code_database.required' => 'The Code Database field is required.',
        ];
    }
}
