<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'tbl_categories';
    protected $primaryKey = 'id';

    // Turn off Laravel's default timestamp columns since your schema uses DATETIME
    // and manually defined created_at/updated_at fields.
    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
