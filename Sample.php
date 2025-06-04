<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $table = 'sample';

    // If you don't have created_at and updated_at columns, disable timestamps
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'company_id', 'sample_name', 'sample_cost', 'length', 'width', 'thickness', 'product_image'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
