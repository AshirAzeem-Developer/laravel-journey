<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'attachments', // JSON field
        'isHot',       // tinyint(1)
        'isActive',    // tinyint(1)
        'category_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'       => 'decimal:2',
        'attachments' => 'json',     // To automatically convert the JSON string to a PHP array/object
        'isHot'       => 'boolean',  // To treat tinyint(1) as boolean
        'isActive'    => 'boolean',  // To treat tinyint(1) as boolean
    ];

    // Note: Since your table uses TIMESTAMP defaults, the default
    // $timestamps = true; will work, but Laravel will handle the date format.
    // If you need DATETIME objects like in your schema, you might need to adjust.


    // --- Relationships ---

    /**
     * Get the category that owns the product (based on category_id).
     */
    public function category(): BelongsTo
    {
        // Assumes a Category model exists and maps to tbl_categories
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the attachments for the product.
     */
    public function attachments(): HasMany
    {
        // Assumes an Attachment model exists and maps to tbl_attachments
        return $this->hasMany(Attachment::class, 'product_id', 'id');
    }

    /**
     * Get the user who created the product (based on created_by).
     */
    public function createdBy(): BelongsTo
    {
        // Assumes your tbl_users maps to a User model.
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated the product (based on updated_by).
     */
    public function updatedBy(): BelongsTo
    {
        // Assumes your tbl_users maps to a User model.
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
