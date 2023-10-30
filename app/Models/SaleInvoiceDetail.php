<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInvoiceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoiceId',
        'productId',
        'quantity',
        'priceU',
        'total',
        'tax',
        'taxBase',
        'taxTotal'
    ];
}
