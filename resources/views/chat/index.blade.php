<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Comsupport AI &mdash; Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Palet Warna Brand (konsisten dengan Pertemuan 4) ── */
        :root {
            --blue-600:   #2563EB;   /* warna utama aksi      */
            --indigo-600: #4F46E5;   /* aksen brand           */
            --indigo-50:  #EEF2FF;   /* latar chip/badge      */
            --indigo-100: #C7D2FE;   /* border aksen          */
            --gray-50:    #F8FAFC;
            --gray-100:   #F1F5F9;
            --gray-200:   #E2E8F0;
            --gray-400:   #94A3B8;
            --gray-500:   #64748B;
            --gray-900:   #0F172A;
            --emerald:    #10B981;   /* status online         */
        }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(14px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .message { animation: fade-in-up .35s cubic-bezier(.16, 1, .3, 1) both; }

        @keyframes typing-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: .35; }
            40%           { transform: translateY(-6px); opacity: 1; }
        }
        .typing-dot { animation: typing-bounce 1.3s infinite ease-in-out both; }
        .typing-dot:nth-child(1) { animation-delay: -.32s; }
        .typing-dot:nth-child(2) { animation-delay: -.16s; }

        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 9999px; }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen items-center justify-center bg-slate-100 p-4 sm:p-6">

        {{-- Chat Window --}}
        <div class="flex h-[85vh] max-h-[820px] min-h-[540px] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            {{-- Header + Tombol Mulai Chat Baru --}}
            <header class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 shadow-md shadow-blue-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
                    </div>
                    <div>
                        <p class="text-[15px] font-bold leading-tight text-gray-900">Comsupport AI</p>
                        <p class="mt-0.5 flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Online &mdash; siap membantu
                        </p>
                    </div>
                </div>

                {{-- Reset: Mulai Chat Baru --}}
                <form action="{{ route('chat.reset') }}" method="POST">
                    @csrf
                    <button type="submit" title="Mulai Chat Baru"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </form>
            </header>

            {{-- Chat Area — render $histories dari database --}}
            <div id="chat-box" class="chat-scroll flex-1 space-y-4 overflow-y-auto scroll-smooth bg-gray-50 px-4 py-6 sm:px-6">
                @if($histories->isEmpty())
                    {{-- Pesan selamat datang hanya saat belum ada riwayat --}}
                    <div class="message flex items-end gap-2.5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/30">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div class="max-w-[78%] rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3 text-sm leading-relaxed text-gray-800 shadow-sm">
                            Halo! Saya <strong class="font-semibold text-gray-900">Comsupport AI</strong>. Tanyakan apapun seputar layanan kami, atau pilih pertanyaan cepat di bawah ini.
                        </div>
                    </div>

                    {{-- Quick Suggestions --}}
                    <div id="quick-suggestions" class="flex flex-wrap gap-2 pl-10">
                        <button type="button" data-question="Bagaimana cara melakukan pemesanan?"
                                class="group inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-4 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 transition-all duration-200 hover:bg-indigo-100 active:scale-95">
                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 1.5a1 1 0 011.7-.7l6.5 6.5a1 1 0 01-.7 1.7H13v8a1 1 0 01-1 1H8a1 1 0 01-1-1V9H3.5a1 1 0 01-.7-1.7l6.5-6.5A1 1 0 019 1.5z"/></svg>
                            Bagaimana cara memesan?
                        </button>
                        <button type="button" data-question="Metode pembayaran apa saja yang tersedia?"
                                class="group inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-4 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 transition-all duration-200 hover:bg-indigo-100 active:scale-95">
                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 12a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                            Metode pembayaran?
                        </button>
                        <button type="button" data-question="Berapa lama estimasi pengiriman?"
                                class="group inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-4 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 transition-all duration-200 hover:bg-indigo-100 active:scale-95">
                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            Estimasi pengiriman?
                        </button>
                    </div>
                @else
                    {{-- Riwayat percakapan dari tabel chat_histories --}}
                    @foreach($histories as $chat)
                    <div class="message flex items-end gap-2.5 {{ $chat->sender === 'user' ? 'flex-row-reverse' : '' }}">
                        @if($chat->sender === 'bot')
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/30">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        @endif
                        <div class="max-w-[78%] rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-sm {{ $chat->sender === 'user' ? 'rounded-br-md bg-blue-600 text-white' : 'rounded-bl-md bg-gray-100 text-gray-800' }}">
                            {{ $chat->message }}
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            {{-- Typing Indicator --}}
            <div id="typing-indicator" class="hidden px-4 pb-3 sm:px-6">
                <div class="flex items-end gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div class="flex items-center gap-1.5 rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3.5 shadow-sm">
                        <div class="h-2 w-2 rounded-full bg-gray-400 typing-dot"></div>
                        <div class="h-2 w-2 rounded-full bg-gray-400 typing-dot"></div>
                        <div class="h-2 w-2 rounded-full bg-gray-400 typing-dot"></div>
                    </div>
                </div>
            </div>

            {{-- Input Area --}}
            <div class="border-t border-gray-100 bg-white px-4 py-4 sm:px-6">
                <div class="flex items-center gap-2">
                    <input type="text" id="user-input"
                        placeholder="Ketik pertanyaanmu di sini..."
                        class="flex-1 rounded-full border-0 bg-gray-100 px-5 py-3.5 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 focus:bg-white focus:ring-2 focus:ring-blue-500/30"
                        maxlength="1000" />
                    <button id="send-btn" title="Kirim pesan"
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white shadow-md shadow-blue-500/30 transition-all duration-200 hover:bg-blue-700 active:scale-95">
                        <svg class="h-5 w-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-2.5 text-center text-[11px] text-gray-400">Comsupport AI menjawab berdasarkan knowledge base FAQ kami.</p>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/chat.js') }}"></script>
</body>
</html>