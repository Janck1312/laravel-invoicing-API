<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("invoiceId")->unsigned();
            $table->bigInteger("productId")->unsigned();
            $table->integer("quantity");
            $table->float("priceU");
            $table->float("total");
            $table->float("tax");
            $table->float("taxBase");
            $table->float("taxTotal");
            $table->timestamps();
            $table->foreign("invoiceId")->on("sale_invoices")->references("id");
            $table->foreign("productId")->on("products")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_details');
    }
};
