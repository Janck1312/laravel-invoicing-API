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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name");
            $table->string("description");
            $table->float("price_purchase");
            $table->float("price");
            $table->integer("stock");
            $table->timestamps();
            $table->bigInteger("brandId")->unsigned();

            $table->unique(["code"]);
            $table->foreign("brandId")->on("brands")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
