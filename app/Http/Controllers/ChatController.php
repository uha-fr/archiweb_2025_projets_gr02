<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Contract;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ChatController extends Controller
{
    public function chat($id)
    {
        $me = Auth::user(); // utilisateur connecté
        $receiver = User::findOrFail($id); // utilisateur qu'on contacte

        return view('chat', [
            'me' => $me,
            'receiver' => $receiver
        ]);
    }



}
