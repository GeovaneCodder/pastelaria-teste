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

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreate;

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
     * Send email on crate a order
     */
    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $client = $order->client()->first();
            Mail::to($client->email)
                ->send(new OrderCreate($client->toArray()));
        });
    }

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
