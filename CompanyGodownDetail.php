<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyGodownDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'godown_name', 'address', 'city', 'pincode', 'user_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
