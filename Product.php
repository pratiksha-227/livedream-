<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function productZonePrices(): HasMany
    {
        return $this->hasMany(ProductZonePrice::class);
    }

    public function getPriceForZone($zoneId)
    {
        return $this->productZonePrices()
            ->where('zone_id', $zoneId)
            ->where('is_active', true)
            ->first();
    }
} 