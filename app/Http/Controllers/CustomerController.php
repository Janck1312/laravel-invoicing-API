<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        //
    }

    public function save(CustomerRequest $request)
    {
        try {
            $id = $request->id;
            $customer = Customer::firstOrNew(["id" => $id]);
            $customer->fill($request->all());
            $customer->save();
            return response()->json(new Collection($customer), 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    public function findById(Request $request) 
    {
        try {
            $id = $request->id;
            $customer = Customer::find(["id"=> $id]);
            if($this->isEmptyObject($customer)) return response()->json('customer not found', 404);
            return response()->json(new Collection($customer));
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
        
    }

    public function deleteById(Request $request) 
    {
        try {
            $id = $request->id;
            $customer = Customer::find(["id" => $id]);
            if ($this->isEmptyObject($customer)) return response()->json('customer not found', 404);
            Customer::destroy($customer);
            return response()->json(["message" => "customer deleted success"], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findAll(Request $request) 
    {
        try {
            $params = $request->query;
            $customers = Customer::query();
            if ($params->get("search")) {
                $customers->where("name", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("lastName", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("identification", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("email", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("id", "LIKE", "%" . $params->get("search") . "%")
                    ->orwhere("born", "LIKE", "%" . $params->get("search") . "%");
            }

            $params->get("limit") ? $customers->limit($params->get("limit")) : $customers->limit(20);
            $params->get("limit") && $params->get("page") ? $customers->skip($params->get("limit") * $params->get("page")) : $customers->skip(20 * 0);
            $total_records = (int) Customer::all()->count();
            return response()->json([
                "data" => new Collection($customers->get()),
                "page" => $params->get("page") ? $params->get("page") : 0,
                "limit" => $params->get("limit") ? $params->get("limit") : 20,
                "skipped" => $params->get("limit") * $params->get("page"),
                "total_records" => $total_records
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    private function isEmptyObject($object)
    {
        foreach ($object as $prop) {
            return false;
        }
        return true;
    }


}
