<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Contract;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{

    public function send(Request $request, $receiverId)
    {
        $receiver = User::findOrFail($receiverId);
        $sender = Auth::user();

        $message = Message::create([
            "receiver_id" => $receiverId,
            "sender_id" => $sender->id,
            "content" => $request->get('content'),
        ]);
        
        // Notifier le destinataire
        $receiver->notify(new NewMessageNotification($message));

        return back();
    }

public function chatcenter($id = null)
{
    $user = Auth::user();

    // Récupérer toutes les conversations 
    $conversations = Message::where('sender_id', $user->id)
        ->orWhere('receiver_id', $user->id)
        ->with(['sender', 'receiver'])
        ->get()
        ->map(function ($message) use ($user) {
            return $message->sender_id === $user->id ? $message->receiver : $message->sender;
        })
        ->unique('id')
        ->values();

    $messages = [];
    $receiver = null;

    if ($id) 
    {
        $receiver = User::findOrFail($id);
        $messages = Message::where(function ($q) use ($user, $receiver) {
            $q->where('sender_id', $user->id)->where('receiver_id', $receiver->id);
        })->orWhere(function ($q) use ($user, $receiver) {
            $q->where('sender_id', $receiver->id)->where('receiver_id', $user->id);
        })->get();
    }

    $users = $conversations;

    return view('chatcenter', compact('user', 'conversations', 'messages', 'receiver', 'users'));
}




}
