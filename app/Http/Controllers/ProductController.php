<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct() 
    {
        //$this->middleware("auth");
    }
    /**
     * @param Request $request
     * @description save a new product Or find and update one current exists
    */
    public function save(ProductRequest $request) 
    {
        try {
            $id = $request->id;
            $product = Product::firstOrNew(["id" => $id]);
            $product->fill($request->all());
            $product->stock = $product->stock ? $product->stock : 0;
            $product->save();
            return response()->json(new Collection($product), 201);
        }catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findAll(Request $request)
    {
        try{
            $params = $request->query;
            $products = Product::query();
            if($params->get("search")) {
                $products->where("name", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("code", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("description", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("stock", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("id", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("brandId", "LIKE", "%" . $params->get("search") . "%");
            }

            $params->get("limit") ? $products->limit($params->get("limit")) : $products->limit(20);
            $params->get("limit") && $params->get("page") ? $products->skip($params->get("limit") * $params->get("page")) : $products->skip(20 * 0);
            $total_records = (int) Product::all()->count(); 
            return response()->json([
                "data" => new Collection($products->get()),
                "page" => $params->get("page") ? $params->get("page") : 0,
                "limit" => $params->get("limit") ? $params->get("limit") : 20,
                "skipped" => $params->get("limit") * $params->get("page"),
                "total_records" => $total_records
            ], 200);
        }catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findById(Request $request) 
    {
        try {
            $id = $request->id;
            $product = Product::find(["id" => $id]);
            if ($this->isEmptyObject($product)) return response()->json("product not found", 404);
            return response()->json(new Collection($product));
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function deleteById(Request $request) 
    {
        try {
            $id = $request->id;
            $product = Product::find(["id" => $id]);
            if($this->isEmptyObject($product)) return response()->json("product not found", 404);
            Product::destroy($product);
            return response()->json(["message" => "product deleted success"], 200);
        } catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    private function isEmptyObject($object) 
    {
        foreach ($object AS $prop) 
        {
            return false;
        }
        return true;
    }
    
}
