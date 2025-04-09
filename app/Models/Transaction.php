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
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
