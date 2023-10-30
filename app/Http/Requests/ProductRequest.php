<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class ProductRequest extends FormRequest
{
    /**
     * The route that users should be redirected to if validation fails.
     *
     * @var string
     */
    //protected $redirect = '/api/products';
    //protected $redirectAction = "App\\Http\\Controllers\\ProductController@responseErr";
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
            'name' => "required",
            'code' => $id ? 'required|unique:products,code,'.$id : 'required|unique:products,code',
            'price_purchase' => "required",
            'description'=> "required",
            //'stock' => "required|int",
            'price'=>"required",
            'brandId' => 'required|exists:brands,id'
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "name is required",
            "code.required" => "code is required",
            "code.unique" => "code already exists",
            "price_purchase.required" => "price purchase is required",
            "description.required" => "description is required",
            //"stock.required" => "name is required"
            "price.required" => "price is required",
            "brandId.required" => "brand is required"
        ];
    }

    /*public function response(array $errors)
    {  
        return new JsonResponse($errors, 422);
    }*/

    /*public function failedValidation(Validator $validator) 
    {
        $errors = (new ValidationException($validator))->errors();
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json($errors)
        );
    }*/
}
