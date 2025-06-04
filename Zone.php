<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'area',
        'user_id',
        'base_price_multiplier',
        'minimum_price',
        'maximum_price',
        'shipping_cost',
        'apply_tax',
        'tax_percentage'
    ];

    protected $casts = [
        'base_price_multiplier' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'maximum_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'apply_tax' => 'boolean',
        'tax_percentage' => 'decimal:2'
    ];

    public function productZonePrices(): HasMany
    {
        return $this->hasMany(ProductZonePrice::class);
    }

    /**
     * Get the price for a specific product in this zone
     */
    public function getProductPrice($productId)
    {
        return $this->productZonePrices()
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Calculate the final price for a product in this zone
     *
     * @param float $basePrice The base price of the product
     * @param int $quantity The quantity of the product
     * @return array Returns an array with price breakdown
     */
    public function calculatePrice(float $basePrice, int $quantity = 1): array
    {
        // Calculate base price with multiplier
        $multipliedPrice = $basePrice * $this->base_price_multiplier;

        // Apply minimum price if set
        if ($this->minimum_price !== null && $multipliedPrice < $this->minimum_price) {
            $multipliedPrice = $this->minimum_price;
        }

        // Apply maximum price if set
        if ($this->maximum_price !== null && $multipliedPrice > $this->maximum_price) {
            $multipliedPrice = $this->maximum_price;
        }

        // Calculate subtotal
        $subtotal = $multipliedPrice * $quantity;

        // Calculate shipping
        $shipping = $this->shipping_cost * $quantity;

        // Calculate tax if applicable
        $tax = 0;
        if ($this->apply_tax) {
            $tax = ($subtotal + $shipping) * ($this->tax_percentage / 100);
        }

        // Calculate total
        $total = $subtotal + $shipping + $tax;

        return [
            'base_price' => $basePrice,
            'multiplied_price' => $multipliedPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total
        ];
    }
}