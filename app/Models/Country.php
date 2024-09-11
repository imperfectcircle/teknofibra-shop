<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'states', 'active'];
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'active' => 'boolean',
    ];

    public function shippingCost() {
        return $this->hasOne(ShippingCost::class, 'country_code', 'code');
    }
}
