<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSample extends Model
{
    use HasFactory;

    protected $table = 'product_sample_imgs';

    protected $fillable = [
        'product_detail_id',
        'product_img_id',
        'image_path'
    ];

    public function productDetail(): BelongsTo
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    public function productImg(): BelongsTo
    {
        return $this->belongsTo(Productimg::class, 'product_img_id');
    }
}
