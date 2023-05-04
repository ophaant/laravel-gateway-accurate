<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => [Rule::requiredIf($this->isMethod('POST')),'string','unique:roles'],
            'display_name' => [Rule::requiredIf($this->isMethod('POST')),'string','max:25'],
            'description' => 'string|nullable',
            'permissions' => [Rule::requiredIf($this->isMethod('POST')),'string'],
        ];
    }
}
