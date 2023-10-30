<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleInvoiceRequest;
use App\Models\Product;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller
{
    public function __construct(){}

    public function save(SaleInvoiceRequest $request) 
    {
        try {
            DB::beginTransaction();
            $sale_details = $request->sale_details;
            $saleInvoice = new SaleInvoice();
            $saleInvoice->fill($request->all());
            $saleInvoice->state = "PENDING";
            $saleInvoice->save();

            foreach($sale_details as $detail) 
            {
                $product = Product::find(["id" => $detail['productId']]);
                $product = $product[0];
                if($this->isEmptyObject($product)) throw new \Exception("product {$detail['productId']} not found", 404);
                if($detail['quantity'] <= 0) throw new \Exception("product {$product->name} {$product->code} quantity cannot be 0 or minor.");
                if($product->stock < $detail['quantity']) throw new \Exception("{$product->name} {$product->code} stock isn't enough");
                $product->stock = $product->stock - $detail['quantity'];
                $product->save();
                $detail['invoiceId'] = $saleInvoice->id;
                $_saleInvoiceDetail = new SaleInvoiceDetail();
                $_saleInvoiceDetail->fill($detail);
                $_saleInvoiceDetail->save();
            }
            $saleInvoice->sale_details = $sale_details;
            DB::commit();
            return response()->json(new Collection($saleInvoice), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    public function findById(Request $request)
    {
        try {
            $id = $request->id;
            $saleInvoice = SaleInvoice::find(["id" => $id]);
            if ($this->isEmptyObject($saleInvoice)) return response()->json("sale invoice not found", 404);
            return response()->json(new Collection($saleInvoice), 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findAll(Request $request)
    {
        try {
            $params = $request->query;
            $sale_invoices = SaleInvoice::query();

            if ($params->get("search")) {
                $sale_invoices->where("serie", "LIKE", "%" . $params->get("search") . "%")
                            ->orwhere("customerId", "LIKE", "%" . $params->get("search") . "%")
                            ->orwhere("sellerId", "LIKE", "%" . $params->get("search") . "%")
                            ->orwhere("state", "LIKE", "%" . $params->get("search") . "%")
                            ->orwhere("id", "LIKE", "%" . $params->get("search") . "%");
            }

            $params->get("limit") ? $sale_invoices->limit($params->get("limit")) : $sale_invoices->limit(20);
            $params->get("limit") && $params->get("page") ? $sale_invoices->skip($params->get("limit") * $params->get("page")) : $sale_invoices->skip(20 * 0);
            $total_records = (int) SaleInvoice::all()->count();
            return response()->json([
                "data" => new Collection($sale_invoices->get()),
                "page" => $params->get("page") ? $params->get("page") : 0,
                "limit" => $params->get("limit") ? $params->get("limit") : 20,
                "skipped" => $params->get("limit") * $params->get("page"),
                "total_records" => $total_records
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function deleteById(Request $request)
    {
        try {
            $id = $request->id;
            $saleInvoice = SaleInvoice::find(["id" => $id]);
            if ($this->isEmptyObject($saleInvoice)) return response()->json("sale invoice not found", 404);
            SaleInvoice::destroy($saleInvoice);
            return response()->json(["message" => "sale invoice deleted success"], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function rejectInvoice(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->id;
            $saleInvoice = SaleInvoice::find(["id" => $id]);
            $saleInvoice = $saleInvoice[0];
            if ($this->isEmptyObject($saleInvoice)) return response()->json("sale invoice not found", 404);
            $saleInvoice->state = "REJECTED";
            $saleInvoice->save();
            $sale_details = SaleInvoiceDetail::where("invoiceId", $saleInvoice->id)->get();
            foreach ($sale_details as $detail) {
                $product = Product::find(["id" => $detail['productId']]);
                $product = $product[0];
                $product->stock = $product->stock + $detail['quantity'];
                $product->save();
            }
            DB::commit();
            return response()->json(new Collection($saleInvoice), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function approveInvoice(Request $request)
    {
        try {
            $id = $request->id;
            $saleInvoice = SaleInvoice::find(["id" => $id]);
            $saleInvoice = $saleInvoice[0]; 
            if($this->isEmptyObject($saleInvoice)) return response()->json("sale invoice not found", 404);
            $saleInvoice->state = "APPROVED";
            $saleInvoice->save();
            return response()->json(new Collection($saleInvoice), 201);
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
