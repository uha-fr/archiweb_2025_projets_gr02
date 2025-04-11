@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Chat avec {{ $receiver->name }}</h2>

        <div id="chat-box" class="space-y-2 mb-6 max-h-[400px] overflow-y-auto px-2 py-4 bg-gray-50 rounded-lg shadow-inner  min-h-[65vh]">
            @foreach ($messages as $message)
                @php
                    $isMe = $message->sender_id === Auth::id();
                @endphp
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs px-4 py-2 rounded-2xl shadow 
                                {{ $isMe ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-200 text-gray-900 rounded-bl-none' }}">
                        <p class="text-sm">
                            {{ $message->content }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('chat.send', $receiver->id) }}" class="flex items-center gap-2">
            @csrf
            <textarea name="content" rows="1" placeholder="Écris ton message..." required
                    class="w-full resize-none rounded-xl border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm shadow">
                Envoyer
            </button>
        </form>

        <script>
            // Scroll auto vers le bas du chat
            window.onload = () => {
                const chatBox = document.getElementById('chat-box');
                if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            };
        </script>
    </div>
    </div>
@endsection