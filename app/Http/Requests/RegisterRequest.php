<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        $emailRules = [
            'required',
            'email:dns,rfc',
        ];

        // tambahkan aturan validasi unique:users pada email jika rute saat ini bukan login
        if (request()->route()->getName() !== 'login') {
            $emailRules[] = Rule::unique('users', 'email');
        }

        return [
            'email' => $emailRules,
            'password' => 'required|min:8',
            'c_password' => [Rule::requiredIf($this->routeIs('register')),'same:password'],
            'permissions' => 'required|string',
        ];
    }
}
