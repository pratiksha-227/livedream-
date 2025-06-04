<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adhesive extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_id',
        'quantity',
        'purchase_cost',
        'selling_price',
        'user_id'
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'adhesive_id');
    }
    public function company()
    
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
