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
                {{-- sticky date --}}
                <div id="sticky-date" class="sticky top-0 z-10 rounded-xl bg-gray-100 text-center py-1 text-sm text-gray-600 shadow-sm max-w-20 opacity-70 mx-auto">
                </div>
                                
             
                    
                @php
                $lastDate = null;
            @endphp
            
            @foreach ($messages as $message)
                @php
                    $currentDate = $message->created_at->format('Y-m-d');
                    $formattedDate = \Carbon\Carbon::parse($currentDate)->isToday() ? 'Today' :
                                     (\Carbon\Carbon::parse($currentDate)->isYesterday() ? 'Yesterday' :
                                     \Carbon\Carbon::parse($currentDate)->translatedFormat('F j'));
                @endphp
            
                @if ($lastDate !== $currentDate)
                    @if ($lastDate !== null)
                        </div> 
                    @endif
            
                    <div class="date-group" data-date="{{ $formattedDate }}">
                @endif
            
                @php $lastDate = $currentDate; @endphp
            
                {{-- Message --}}
                <div class="flex  mb-2  {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-start space-x-2 group relative"> <!-- 👈 ajout de 'group' ici -->
                                           {{-- 3 points visibles seulement au hover --}}
                                           @php $isMe = $message->sender_id === Auth::id(); @endphp
                                           @if ($isMe)
                                           
                                           <div class="mr-2">
                                               <button onclick="toggleMenu('menu-{{ $message->id }}')" 
                                                       class="text-gray-500 hover:text-gray-700 hidden group-hover:block focus:outline-none">
                                                   &#8942;
                                               </button>
                                   
                                               {{-- Menu  --}}
                                               <div id="menu-{{ $message->id }}" 
                                                    class="absolute z-10 mt-2 w-28 bg-white shadow-md rounded-md hidden">
                                                   <form method="POST" action="{{ route('chat.destroy', $message->id) }}" 
                                                         onsubmit="return confirm('Supprimer ce message ?')">
                                                       @csrf
                                                       @method('DELETE')
                                                       <button type="submit" 
                                                               class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 text-red-500">
                                                           Supprimer
                                                       </button>
                                                   </form>
                                               </div>
                                           </div>
                                       @endif
                   
                   
                    <div class="max-w-xs px-4 py-2 rounded-2xl shadow 
                        {{ $message->sender_id === Auth::id() ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-200 text-gray-900 rounded-bl-none' }}">
                        <p class="text-sm">{{ $message->content }}</p>
                        <p class="text-[10px] mt-1 text-right opacity-70">
                            {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                        </p>
                    </div>
                    </div>
                </div>
            @endforeach
            
            </div>
            


                 
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


</script>
<script>
    function toggleMenu(id) {
        const menu = document.getElementById(id);
        // Ferme tous les autres menus ouverts
        document.querySelectorAll('[id^="menu-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });

        menu.classList.toggle('hidden');
    }

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', function (event) {
        if (!event.target.closest('button') && !event.target.closest('[id^="menu-"]')) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dateGroups = document.querySelectorAll('.date-group');
        const stickyDate = document.getElementById('sticky-date');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const date = entry.target.getAttribute('data-date');
                    stickyDate.textContent = date;
                }
            });
        }, {
            root: document.getElementById('chat-box'),
            threshold: 0.5
        });

        dateGroups.forEach(group => observer.observe(group));
    });
</script>




@endsection
