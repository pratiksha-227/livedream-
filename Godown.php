<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'address', 'city', 'pincode',
    ];

    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

