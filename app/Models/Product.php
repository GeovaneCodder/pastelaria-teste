<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes,
    Factories\HasFactory,
    Relations\BelongsTo,
};
class Product extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * 
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price',
        'photo',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
