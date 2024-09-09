<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'percentage', 'is_active'];

    protected $casts = [
        'percentage' => 'float',
        'is_active' => 'boolean',
    ];
}
