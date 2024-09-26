<?php

namespace App\Models;

use App\Models\Order;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes,
    Factories\HasFactory,
    Relations\HasMany,
};

class Client extends Model
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
        'email',
        'phone_number',
        'birthday',
        'address',
        'complement',
        'neighborhood',
        'postal_code',
    ];

    /**
     * 
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
