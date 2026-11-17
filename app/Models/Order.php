<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'ref', 'user_id', 'name', 'email', 'phone', 'nif',
        'address', 'postal_code', 'city', 'billing_same',
        'billing_name', 'billing_address', 'billing_postal_code', 'billing_city',
        'notes', 'shipping_method', 'shipping_cost', 'payment_method',
        'subtotal', 'total', 'status', 'items_snapshot',
        'transfer_proof_path', 'transfer_proof_name',
    ];

    protected $casts = [
        'items_snapshot' => 'array',
        'shipping_cost'  => 'decimal:2',
        'subtotal'       => 'decimal:2',
        'total'          => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingLabel(): string
    {
        return \App\Http\Controllers\CheckoutController::SHIPPING[$this->shipping_method]['label'] ?? $this->shipping_method;
    }

    public function paymentLabel(): string
    {
        return \App\Http\Controllers\CheckoutController::PAYMENT[$this->payment_method]['label'] ?? $this->payment_method;
    }

    public function money($value): string
    {
        return number_format((float) $value, 2, ',', ' ').'€';
    }
}
