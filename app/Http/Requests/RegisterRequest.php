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
           Rule::requiredIf($this->routeIs('register')),
            'email:dns,rfc',
        ];

        // tambahkan aturan validasi unique:users pada email jika rute saat ini bukan login
        if (request()->route()->getName() !== 'login') {
            $emailRules[] = Rule::unique('users', 'email');
        }

        return [
            'name' => [Rule::requiredIf($this->routeIs('register')),'string','max:255'],
            'email' => $emailRules,
            'password' => [Rule::requiredIf($this->routeIs('register','login')),'min:8'],
            'c_password' => [Rule::requiredIf($this->routeIs('register')),'same:password'],
            'permissions' => [Rule::requiredIf($this->routeIs('register')),'string'],
        ];
    }
}
