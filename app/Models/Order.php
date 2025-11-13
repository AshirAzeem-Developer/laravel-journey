<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'tbl_orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'payment_status',
        'order_status',
        'shipping_address',
        'billing_address',
        'payment_method',
        'transaction_id', // Add this if not already in database
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Payment method constants
    public const PAYMENT_COD = 'cash_on_delivery';
    public const PAYMENT_PAYPAL = 'paypal';
    public const PAYMENT_STRIPE = 'stripe';

    // Payment status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    // Order status constants
    public const ORDER_PROCESSING = 'processing';
    public const ORDER_CONFIRMED = 'confirmed';
    public const ORDER_SHIPPED = 'shipped';
    public const ORDER_DELIVERED = 'delivered';
    public const ORDER_CANCELLED = 'cancelled';

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Accessors - Get human-readable values
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            self::PAYMENT_COD => 'Cash on Delivery',
            self::PAYMENT_PAYPAL => 'PayPal',
            self::PAYMENT_STRIPE => 'Credit/Debit Card (Stripe)',
            default => 'Unknown Payment Method',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded',
            default => ucfirst($this->payment_status),
        };
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            self::ORDER_PROCESSING => 'Processing',
            self::ORDER_CONFIRMED => 'Confirmed',
            self::ORDER_SHIPPED => 'Shipped',
            self::ORDER_DELIVERED => 'Delivered',
            self::ORDER_CANCELLED => 'Cancelled',
            default => ucfirst($this->order_status),
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            self::STATUS_PAID => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_FAILED => 'danger',
            self::STATUS_REFUNDED => 'info',
            default => 'secondary',
        };
    }

    public function getOrderStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            self::ORDER_DELIVERED => 'success',
            self::ORDER_SHIPPED => 'info',
            self::ORDER_CONFIRMED => 'primary',
            self::ORDER_PROCESSING => 'warning',
            self::ORDER_CANCELLED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Scopes - Query helpers
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', self::ORDER_PROCESSING);
    }

    public function scopeByPaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Helper methods
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }

    public function isCancelled(): bool
    {
        return $this->order_status === self::ORDER_CANCELLED;
    }

    public function isDelivered(): bool
    {
        return $this->order_status === self::ORDER_DELIVERED;
    }

    public function isCashOnDelivery(): bool
    {
        return $this->payment_method === self::PAYMENT_COD;
    }

    public function isPayPal(): bool
    {
        return $this->payment_method === self::PAYMENT_PAYPAL;
    }

    public function isStripe(): bool
    {
        return $this->payment_method === self::PAYMENT_STRIPE;
    }

    /**
     * Calculate total items count
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rs. ' . number_format($this->total_amount, 2);
    }
}
