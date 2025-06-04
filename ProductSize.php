<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_detail_id',
        'key',
        'value',
        'unit'
    ];

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }
} 