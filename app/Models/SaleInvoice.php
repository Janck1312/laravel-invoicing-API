<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customerId',
        'serie',
        'total',
        'tax',
        'taxBase',
        'taxTotal',
        'sellerId',
        'state'
    ];
}
