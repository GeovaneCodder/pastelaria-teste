<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Factories\HasFactory,
    SoftDeletes
};

class OrderProductBond extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * 
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'product_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
