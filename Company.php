<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'gst',
        'city',
        'pincode',
        'type',
        'relation',
        'product_category',
        
        'user_id',
        'is_active'
    ];

  

    public function godowns()
    {
        return $this->hasMany(Godown::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
