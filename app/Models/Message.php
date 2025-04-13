<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
   
    protected $fillable = [
        "receiver_id" ,
        "sender_id",
        "content" ,
    ];

     /**
     * Relation avec l'utilisateur qui a envoyé le message (sender).
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relation avec l'utilisateur qui a reçu le message (receiver).
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}
