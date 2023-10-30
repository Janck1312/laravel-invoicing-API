<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post("products", [ProductController::class, 'save']);
Route::put("products/{id}", [ProductController::class, 'save']);
Route::get("products", [ProductController::class, 'findAll']);
Route::get("products/{id}", [ProductController::class, 'findById']);
Route::delete("products/{id}", [ProductController::class, "deleteById"]);

Route::post("customers", [CustomerController::class, "save"]);
Route::put("customers/{id}", [CustomerController::class, "save"]);
Route::delete("customers/{id}", [CustomerController::class, 'deleteById']);
Route::get("customers",[CustomerController::class, 'findAll']);
Route::get("customers/{id}", [CustomerController::class, 'findById']);

Route::post("saleInvoices", [SaleInvoiceController::class, "save"]);
Route::get("saleInvoices/{id}", [SaleInvoiceController::class, "findById"]);
Route::get("saleInvoices", [SaleInvoiceController::class, 'findAll']);
Route::put("saleInvoices/reject/{id}",[SaleInvoiceController::class, "rejectInvoice"]);
Route::put("saleInvoices/approve/{id}", [SaleInvoiceController::class, "approveInvoice"]);

Route::post("users", [UserController::class, 'save']);
