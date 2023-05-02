<?php

namespace App\Http\Requests;

use App\Enums\WhitelistStatusEnum;
use App\Enums\WhitelistTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class WhitelistRequest extends FormRequest
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
            'ip' => [Rule::requiredIf($this->isMethod('POST')),'ipv4','unique:block_ips,ip'],
            'description' => 'string|nullable',
            'status' => [new Enum(WhitelistStatusEnum::class)],
            'type' => [new Enum(WhitelistTypeEnum::class)]
        ];
    }
}
