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

class ChatController extends Controller
{
    public function chat($id)
    {
       $receiver = User::findOrFail($id);
       $sender = Auth::user();

       $messages = Message::where('receiver_id',"=",$receiver->id)->where('sender_id',"=",$sender->id)->orWhere('sender_id',"=",$receiver->id)->where('receiver_id',$sender->id)->get();
      //  dd($messages);

       return view('chat',[
            'sender' => $sender,
            'receiver' => $receiver,
            'messages' => $messages
       ]);

    }

    public function send(Request $request, $receiverId)
    {
        $receiver = User::findOrFail($receiverId);
        $sender = Auth::user();

        $message = Message::create([
            "receiver_id" => $receiverId,
            "sender_id" => $sender->id,
            "content" => $request->get('content'),
        ]);

        return back();
    }
}
