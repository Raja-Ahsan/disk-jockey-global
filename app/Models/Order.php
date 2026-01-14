<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'order_number', 'subtotal', 'tax', 'shipping_cost', 'total_amount',
        'status', 'payment_status', 'payment_method', 'stripe_payment_intent_id',
        'tracking_number', 'shipped_at',
        'shipping_name', 'shipping_email', 'shipping_phone',
        'shipping_address_line1', 'shipping_address_line2', 'shipping_city',
        'shipping_state', 'shipping_zipcode', 'shipping_country', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'shipped_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFullShippingAddressAttribute()
    {
        $address = $this->shipping_address_line1;
        if ($this->shipping_address_line2) {
            $address .= ', ' . $this->shipping_address_line2;
        }
        $address .= ', ' . $this->shipping_city . ', ' . $this->shipping_state . ' ' . $this->shipping_zipcode;
        return $address;
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['shipped', 'delivered']);
    }
}
