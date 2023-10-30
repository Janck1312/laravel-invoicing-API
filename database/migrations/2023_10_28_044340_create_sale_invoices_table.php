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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("customerId")->unsigned();
            $table->string("serie")->unique();
            $table->float("total");
            $table->float("tax");
            $table->float("taxBase");
            $table->float("taxTotal");
            $table->bigInteger("sellerId")->unsigned();
            $table->enum("state", ["PENDING", "REJECTED", "APPROVED"]);
            $table->timestamps();

            $table->foreign("customerId")->on("customers")->references("id");
            $table->foreign("sellerId")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
