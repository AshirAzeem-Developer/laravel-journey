<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'tbl_cart';
    protected $primaryKey = 'id';

    // The table only has a single `created_at` timestamp
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        // Assumes your primary user table, tbl_users, maps to the User model
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
