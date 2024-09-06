<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function shippingCost() {
        return $this->hasOne(ShippingCost::class, 'country_code', 'code');
    }
}
