<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'productdetails';

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'application_area',
        'length',
        'width',
        'thickness',
        'unit',
        'other_parameters',
        'adhesive_id',
        'labor_charges',
        'delivery_duration',
        'delivery_unit',
        'gst_percentage',
        'warranty_period',
        'warranty_type',
    ];

    protected $casts = [
        'other_parameters' => 'array',
         // Cast JSON column to array
        'gst_percentage' => 'decimal:2',
        'warranty_period' => 'integer',
    ];

    // Relationships

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function adhesive()
    {
        return $this->belongsTo(Adhesive::class);
    }

    public function images()
    {
        return $this->hasMany(Productimg::class, 'productdetail_id');
    }

    public function sampleImages()
    {
        return $this->hasMany(ProductSample::class, 'product_detail_id');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_detail_id');
    }
}
