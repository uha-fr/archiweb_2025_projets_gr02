<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'quantity',
        'price',
        'transaction_time',
        'user_id',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
