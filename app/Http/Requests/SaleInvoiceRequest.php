<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaleInvoiceRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        $id = $request->id;
        return [
            'serie' => $id ? "required|unique:sale_invoices,serie,".$id : "required|unique:sale_invoices,serie",
            'customerId' => "required|exists:customers,id",
            'total' =>"required",
            'tax' =>"required",
            'taxBase' =>"required",
            'taxTotal' =>"required",
            'sellerId' =>"required|exists:users,id",
            "sale_details" => "required",
            //'state' =>"required",
        ];
    }
}
