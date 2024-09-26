<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes,
    Factories\HasFactory,
    Relations\BelongsTo,
    Relations\HasManyThrough
};

use App\Models\{
    Product,
    OrderProductBond
};

class Order extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * 
     * @var string[]
     */
    protected $fillable = [
        'client_id',
    ];

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            OrderProductBond::class,
            'order_id',
            'id',
            'id',
            'product_id'
        );
    }
}
