<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'tbl_attachments';
    protected $primaryKey = 'attachment_id';

    public $timestamps = false; // Disable default timestamps

    protected $fillable = [
        'product_id',
        'file_name',
        'file_type',
        'file_size',
        'file_url',
        'is_primary',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean', // tinyint(1)
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
