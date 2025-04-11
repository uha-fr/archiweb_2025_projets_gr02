<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'company_name',
        'tax_id',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Get the offers for the user.
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get the contracts for the user.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'buyer_id')
                    ->orWhere('seller_id', $this->id);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Contract::class, 'buyer_id')
                    ->orWhere('seller_id', $this->id);
    }

    /**
     * Get the number of pending contracts where the user is the seller.
     *
     * @return int
     */
    public function pendingContractsAsSellerCount()
    {
        return Contract::where('seller_id', $this->id)
                      ->where('status', 'pending')
                      ->count();
    }
}
