<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSale extends Pivot
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function total(): float
    {
        return $this->product->price * $this->pivot->amount;
    }
}
