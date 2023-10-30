<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $id = $request->id;

        return [
            'lastName' => "required",
            'name' => "required",
            'identification' => $id ? "required|unique:customers,identification,".$id : "required|unique:customers,identification",
            'email' => $id ? "required|email|unique:customers,email,".$id : "required|email|unique:customers,email",
            'born' => "required|date",
            'address' => "required|max:255"
        ];
    }
}
