<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'total_price', 'shipping_cost', 'created_by', 'updated_by'];

    public function isPaid() {
        return $this->status === OrderStatus::Paid->value;
    }

    public function payment(): HasOne {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
