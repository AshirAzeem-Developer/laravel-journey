<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Added for API authentication
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'designation',
        'file_path',
        'phone_number',
        'address',
        'city',
        'postcode',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'postcode' => 'integer',
            'created_at' => 'datetime', // Your schema uses TIMESTAMP/DATETIME
            'updated_at' => 'datetime', // Your schema uses DATETIME
        ];
    }


    // --- Relationships (Based on your schema) ---

    /**
     * Get the orders associated with the user.
     */
    public function orders(): HasMany
    {
        // One user can have many orders (tbl_orders.user_id)
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * Get the cart items associated with the user.
     */
    public function cartItems(): HasMany
    {
        // One user can have many cart items (tbl_cart.user_id)
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    /**
     * Get the products created by this user.
     */
    public function productsCreated(): HasMany
    {
        // One user can create many products (tbl_products.created_by)
        return $this->hasMany(Product::class, 'created_by', 'id');
    }

    /**
     * Get the user who created this user record.
     */
    public function creator(): BelongsTo
    {
        // Self-referencing relationship: created_by points back to another user's id
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this user record.
     */
    public function updater(): BelongsTo
    {
        // Self-referencing relationship: updated_by points back to another user's id
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
