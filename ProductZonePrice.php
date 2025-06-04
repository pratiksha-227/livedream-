<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductZonePrice extends Model
{
    protected $fillable = [
        'product_id',
        'zone_id',
        'selling_price',
        'discount_price',
        'is_active'
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
} 