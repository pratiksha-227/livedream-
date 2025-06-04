<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Productimg extends Model
{
    protected $fillable = [
        'productdetail_id',
        'image_path',
        'pdf_name',
        'product_code',
        'product_color',
        'purchase_cost',
        'selling_price',
        'discount_price',
        'stock_available'
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_available' => 'boolean'
    ];

    public function productDetail(): BelongsTo
    {
        return $this->belongsTo(ProductDetail::class, 'productdetail_id');
    }
} 