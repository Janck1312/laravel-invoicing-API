<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'price_purchase',
        'price',
        'stock',
        'created_at',
        'updated_at',
        'brandId'
    ];

    protected $casts = [
        'name' => 'string',
        'stock' => "integer",
    ];
}
