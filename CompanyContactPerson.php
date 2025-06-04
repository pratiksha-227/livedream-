<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyContactPerson extends Model
{
    use HasFactory;
    protected $table = "company_contact_persons";
    protected $fillable = [
        'company_id', 'name', 'designation', 'email', 'phone', 'user_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
