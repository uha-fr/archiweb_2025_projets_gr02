@extends('layouts.app')

@section('content')
<div class="flex h-[80vh] max-w-7xl mx-auto">
    {{-- Side bar utilisateurs --}}
    <div class="w-1/3 bg-white border-r overflow-y-auto shadow">
        <div class="p-4 font-semibold text-lg border-b">Conversations</div>
        @forelse ($users as $u)
            <a href="{{ route('chat.with', $u->id) }}" class="flex items-center px-4 py-3 hover:bg-gray-100 border-b {{ isset($receiver) && $receiver->id == $u->id ? 'bg-gray-100' : '' }}">
                <img src="{{ $u->profile_photo_url }}" alt="{{ $u->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                <span class="text-gray-800">{{ $u->name }}</span>
            </a>
        @empty
            <p class="p-4 text-gray-500">Aucune conversation</p>
        @endforelse
    </div>

    {{-- Fenêtre de conversation --}}
    <div class="w-2/3 flex flex-col justify-between">
        @if ($receiver)
            <div class="border-b px-6 py-4 font-medium text-gray-800 shadow">
                Conversation avec {{ $receiver->name }}
            </div>

            <div id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 space-y-2 bg-gray-50">
                @foreach ($messages as $message)
                    @php $isMe = $message->sender_id === Auth::id(); @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-4 py-2 rounded-2xl shadow 
                                    {{ $isMe ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-200 text-gray-900 rounded-bl-none' }}">
                            <p class="text-sm">{{ $message->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('chat.send', $receiver->id) }}" class="flex items-center gap-2 border-t px-4 py-3">
                @csrf
                <textarea name="content" rows="1" placeholder="Écris ton message..." required
                    class="w-full resize-none rounded-xl border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm shadow">
                    Envoyer
                </button>
            </form>
        @else
            <div class="flex-1 flex items-center justify-center text-gray-400">
                <p>Sélectionne une conversation pour commencer</p>
            </div>
        @endif
    </div>
</div>

<script>
    window.onload = () => {
        const chatBox = document.getElementById('chat-box');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    };
</script>
@endsection
