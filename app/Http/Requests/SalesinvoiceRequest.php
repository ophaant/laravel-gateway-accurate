<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesinvoiceRequest extends FormRequest
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
            'code_database' => 'required|integer',
            "customerName" => "required|string",
            "transDate" => "required|date_format:d/m/Y",
            "branchName" => "required|string",
            "description" => "string|nullable",
            "paymentTermName" => "required|string",
            "shipDate" => "required|date_format:d/m/Y",
            "detailItem" => "required|array",
            "detailItem.*.itemNo" => "required|string",
            "detailItem.*.unitPrice" => "required|numeric",
            "detailItem.*.quantity" => "required|integer",
            "detailItem.*.warehouseName" => "required|string",
        ];
    }

    public function messages()
    {
        return [
            'code_database.required' => 'The Code Database field is required.',
        ];
    }
}
