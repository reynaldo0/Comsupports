<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Comsupport AI — Bantuan pelanggan 24/7 berbasis Gemini AI.">
    <title>Comsupport AI &mdash; Bantuan Pelanggan 24/7</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Palet warna brand (konsisten dengan halaman /chat) */
        :root {
            --blue-600:   #2563EB;   /* warna utama aksi  */
            --blue-700:   #1D4ED8;   /* primary hover     */
            --indigo-600: #4F46E5;   /* aksen brand       */
            --indigo-50:  #EEF2FF;
            --indigo-100: #C7D2FE;
            --gray-50:    #F8FAFC;
            --gray-100:   #F1F5F9;
            --gray-200:   #E2E8F0;
            --gray-400:   #94A3B8;
            --gray-500:   #64748B;
            --gray-600:   #475569;
            --gray-900:   #0F172A;
            --emerald:    #10B981;   /* status online     */
        }

        /* Animasi halus: muncul bertahap, mengambang, dan titik mengetik */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }
        @keyframes typing-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: .35; }
            40%           { transform: translateY(-4px); opacity: 1; }
        }

        .animate-fade-up      { animation: fade-up .6s ease-out both; }
        .animate-fade-up-slow { animation: fade-up .9s ease-out both; animation-delay: .15s; }
        .animate-float        { animation: float 6s ease-in-out infinite; }
        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: #94A3B8;
            animation: typing-bounce 1.2s ease-in-out infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: .2s; }
        .typing-dot:nth-child(3) { animation-delay: .4s; }

        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900">
    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="/" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </span>
                <span class="text-[15px] font-bold tracking-tight text-gray-900">Comsupport&nbsp;AI</span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-gray-600 md:flex">
                <a href="#fitur" class="transition-colors hover:text-gray-900">Fitur</a>
                <a href="#cara-kerja" class="transition-colors hover:text-gray-900">Cara Kerja</a>
                <a href="#testimoni" class="transition-colors hover:text-gray-900">Testimoni</a>
                <a href="#faq" class="transition-colors hover:text-gray-900">FAQ</a>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                       class="hidden items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100 sm:inline-flex">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-gray-600 transition-colors hover:text-gray-900 sm:inline-flex">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="hidden rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100 sm:inline-flex">
                        Daftar
                    </a>
                @endauth
                <a href="{{ route('chat.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                    Mulai Chat
                </a>
            </div>

            <button id="mobile-menu-btn" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 transition-colors hover:bg-gray-100 md:hidden"
                    aria-label="Buka menu navigasi">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Menu mobile --}}
        <nav id="mobile-menu" class="hidden border-t border-gray-100 bg-white px-4 py-4 md:hidden">
            <div class="flex flex-col gap-1">
                <a href="#fitur" class="rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900">Fitur</a>
                <a href="#cara-kerja" class="rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900">Cara Kerja</a>
                <a href="#testimoni" class="rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900">Testimoni</a>
                <a href="#faq" class="rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900">FAQ</a>
            </div>
            <div class="mt-3 flex flex-col gap-2 border-t border-gray-100 pt-3">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-100">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700">
                        Daftar
                    </a>
                @endauth
                <a href="{{ route('chat.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">
                    Mulai Chat
                </a>
            </div>
        </nav>
    </header>

    <main>
        {{-- Hero --}}
        <section class="border-b border-gray-100 bg-gray-50">
            <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-2 lg:gap-16">
                <div class="animate-fade-up">
                    <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-gray-600 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Asisten AI 24/7
                    </div>
                    <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl">
                        Bantuan pelanggan,
                        <span class="relative">
                            <span class="absolute inset-x-0 bottom-1 h-3 rounded-md bg-blue-100 sm:h-4" aria-hidden="true"></span>
                            <span class="relative">tanpa menunggu</span>
                        </span>
                    </h1>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-gray-500 sm:text-lg">
                        Comsupport AI menjawab pertanyaan pelanggan secara instan berdasarkan knowledge base FAQ kamu — 24 jam sehari, tanpa antre.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('chat.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                            Mulai Chat Sekarang
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="#fitur"
                           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100">
                            Lihat Fitur
                        </a>
                    </div>
                    <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-50 bg-blue-600 text-[10px] font-bold text-white">AR</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-50 bg-indigo-500 text-[10px] font-bold text-white">DS</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-50 bg-emerald-500 text-[10px] font-bold text-white">NF</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900"><span class="text-amber-400" aria-hidden="true">&starf;&starf;&starf;&starf;&starf;</span> 4.9/5</p>
                                <p class="text-xs text-gray-500">Kepuasan pengguna</p>
                            </div>
                        </div>
                        <div class="hidden h-8 w-px bg-gray-200 sm:block" aria-hidden="true"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">120+ tim</p>
                            <p class="text-xs text-gray-500">sudah menggunakannya</p>
                        </div>
                    </div>
                </div>

                {{-- Preview Chat --}}
                <div class="mx-auto w-full max-w-md animate-fade-up-slow">
                    <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm animate-float">
                        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                            <div class="relative">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                </span>
                                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">Comsupport AI</p>
                                <p class="text-xs text-gray-500">Online &mdash; siap membantu</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-600">id-ID</span>
                        </div>

                        <div class="space-y-3 px-1 py-5">
                            <div class="flex items-end gap-2.5">
                                <div class="max-w-[85%] rounded-2xl rounded-bl-md bg-gray-100 px-4 py-2.5 text-sm text-gray-800">Halo! Ada yang bisa saya bantu?</div>
                            </div>
                            <div class="flex flex-row-reverse items-end gap-2.5">
                                <div class="max-w-[85%] rounded-2xl rounded-br-md bg-blue-600 px-4 py-2.5 text-sm text-white">Bagaimana cara melakukan pemesanan?</div>
                            </div>
                            <div class="flex items-end gap-2.5" aria-hidden="true">
                                <div class="flex items-center gap-1.5 rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3.5">
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                    <span class="typing-dot"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 border-t border-gray-100 pt-4">
                            <div class="flex-1 rounded-full bg-gray-100 px-4 py-3 text-sm text-gray-400">Ketik pertanyaanmu...</div>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                <svg class="h-4 w-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Logo Band --}}
        <section class="bg-white py-14">
            <div class="mx-auto max-w-6xl px-4 text-center sm:px-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Dipercaya tim layanan dari berbagai industri</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-x-12 gap-y-6 text-lg font-extrabold tracking-tight text-gray-300">
                    <span>Lumina</span>
                    <span>Finpay</span>
                    <span>Medica+</span>
                    <span>Kopi Nusantara</span>
                    <span>Safari Go</span>
                </div>
            </div>
        </section>

        {{-- Fitur --}}
        <section id="fitur" class="scroll-mt-20 border-t border-gray-100 bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="max-w-xl">
                    <p class="text-sm font-bold text-blue-600">Fitur</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Kenapa memilih Comsupport AI?</h2>
                    <p class="mt-3 text-sm leading-relaxed text-gray-500">Tiga alasan utama tim layanan pelanggan beralih ke chatbot berbasis Gemini.</p>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-colors hover:border-blue-200">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">Jawaban Instan</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Pelanggan mendapat jawaban langsung dalam hitungan detik, tanpa menunggu antrian customer service.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-colors hover:border-indigo-200">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">Knowledge Base Terkelola</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Jawaban diambil dari FAQ yang dikelola admin lewat dashboard — selalu relevan dengan produkmu.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-colors hover:border-emerald-200">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">Online 24/7</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Bot aktif nonstop — di jam kerja, malam, libur, dan hari raya, tanpa lembur.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Cara Kerja --}}
        <section id="cara-kerja" class="scroll-mt-20 border-t border-gray-100 bg-gray-50 py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="text-center">
                    <p class="text-sm font-bold text-blue-600">Cara Kerja</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Dari pertanyaan ke jawaban dalam 3 langkah</h2>
                    <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-gray-500">Tanpa instalasi rumit — cukup buka chat dan tanyakan apa pun.</p>
                </div>
                <div class="relative mt-12 grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-8">
                    <div class="hidden border-t-2 border-dashed border-gray-200 md:absolute md:inset-x-[16%] md:top-6 md:block" aria-hidden="true"></div>
                    <div class="relative text-center md:px-4">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-base font-extrabold text-white shadow-sm">1</span>
                        <h3 class="mt-5 text-base font-semibold text-gray-900">Pengunjung bertanya</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Pelanggan mengetik pertanyaan langsung di /chat — tanpa login atau antre menunggu.</p>
                    </div>
                    <div class="relative text-center md:px-4">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-base font-extrabold text-white shadow-sm">2</span>
                        <h3 class="mt-5 text-base font-semibold text-gray-900">AI membaca knowledge base</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Gemini mencocokkan pertanyaan dengan FAQ yang dikelola admin di dashboard.</p>
                    </div>
                    <div class="relative text-center md:px-4">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-base font-extrabold text-white shadow-sm">3</span>
                        <h3 class="mt-5 text-base font-semibold text-gray-900">Jawaban muncul beberapa detik kemudian</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">Respons dikirim langsung dan tersimpan di riwayat percakapan setiap user.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Statistik --}}
        <section class="bg-gray-900 py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid grid-cols-2 gap-10 text-center lg:grid-cols-4">
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-white">24/7</p>
                        <p class="mt-1 text-sm text-gray-400">Selalu aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-white">&lt; 3 dtk</p>
                        <p class="mt-1 text-sm text-gray-400">Respons rata-rata</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-white">120+</p>
                        <p class="mt-1 text-sm text-gray-400">Tim pengguna</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-white">100%</p>
                        <p class="mt-1 text-sm text-gray-400">Berbasis knowledge base</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Testimoni --}}
        <section id="testimoni" class="scroll-mt-20 border-t border-gray-100 bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="text-center">
                    <p class="text-sm font-bold text-blue-600">Testimoni</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Kata mereka tentang Comsupport AI</h2>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-3">
                    <figure class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <span class="text-amber-400" aria-hidden="true">&starf;&starf;&starf;&starf;&starf;</span>
                        <blockquote class="mt-4 text-sm leading-relaxed text-gray-600">&ldquo;Sejak memakai Comsupport AI, ticket chat yang harus kami jawab manual turun drastis.&rdquo;</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">RS</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Rina Saputri</p>
                                <p class="text-xs text-gray-500">CS Lead, Lumina Retail</p>
                            </div>
                        </figcaption>
                    </figure>
                    <figure class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <span class="text-amber-400" aria-hidden="true">&starf;&starf;&starf;&starf;&starf;</span>
                        <blockquote class="mt-4 text-sm leading-relaxed text-gray-600">&ldquo;Pelanggan kami selalu bisa dapat jawaban cepat bahkan di luar jam kantor.&rdquo;</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-500 text-xs font-bold text-white">DB</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Dimas Bayu</p>
                                <p class="text-xs text-gray-500">Founder, Kopi Nusantara</p>
                            </div>
                        </figcaption>
                    </figure>
                    <figure class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <span class="text-amber-400" aria-hidden="true">&starf;&starf;&starf;&starf;&starf;</span>
                        <blockquote class="mt-4 text-sm leading-relaxed text-gray-600">&ldquo;Update knowledge base ke dashboard sangat mudah, jawaban bot langsung akurat.&rdquo;</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">NL</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Nadia Lestari</p>
                                <p class="text-xs text-gray-500">Product Manager, Finpay</p>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section id="faq" class="scroll-mt-20 border-t border-gray-100 bg-gray-50 py-16 sm:py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6">
                <div class="text-center">
                    <p class="text-sm font-bold text-blue-600">FAQ</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900">Pertanyaan yang sering diajukan</h2>
                </div>
                <div class="mt-10 space-y-3">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-gray-900">Bagaimana cara melakukan pemesanan?</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">Pemesanan bisa dilakukan langsung lewat aplikasi atau menghubungi tim support kami.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-gray-900">Metode pembayaran apa saja yang tersedia?</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">Kami menerima transfer bank, e-wallet, dan kartu melalui kanal pembayaran resmi.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-gray-900">Berapa lama estimasi pengiriman?</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">Estimasi bervariasi tergantung lokasi — biasanya 1-3 hari kerja.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-gray-900">Bagaimana cara mengembalikan barang?</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">Hubungi tim support untuk panduan pengembalian dalam 7 hari setelah barang diterima.</p>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('chat.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition-colors hover:text-blue-700">
                        Pertanyaan lain? Tanyakan langsung ke chatbot
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- CTA Panel --}}
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <div class="rounded-3xl bg-gray-900 px-6 py-14 text-center sm:py-16">
                <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Butuh bantuan sekarang?</h2>
                <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-gray-400">Asisten AI kami siap menjawab pertanyaanmu kapan saja, di mana saja — tanpa antre.</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('chat.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                        Mulai Chat Gratis
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-gray-800">
                        Buat Akun
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 pt-16 pb-8 text-gray-400">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="grid grid-cols-2 gap-10 md:grid-cols-4">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </span>
                        <span class="text-[15px] font-bold tracking-tight text-white">Comsupport&nbsp;AI</span>
                    </div>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed">Asisten pelanggan berbasis Gemini AI yang menjawab 24/7 dari knowledge base FAQ kamu.</p>
                    <div class="mt-5 flex items-center gap-2">
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-800 text-gray-400 transition-colors hover:bg-gray-700 hover:text-white" aria-label="X">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-800 text-gray-400 transition-colors hover:bg-gray-700 hover:text-white" aria-label="GitHub">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56v-1.97c-3.2.7-3.87-1.54-3.87-1.54-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.72-1.54-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.28 1.18-3.09-.12-.29-.51-1.46.11-3.04 0 0 .96-.31 3.15 1.18a10.9 10.9 0 015.74 0c2.19-1.49 3.15-1.18 3.15-1.18.62 1.58.23 2.75.11 3.04.73.81 1.18 1.83 1.18 3.09 0 4.42-2.7 5.39-5.26 5.68.41.35.77 1.05.77 2.12v3.15c0 .31.21.67.8.56A10.52 10.52 0 0023.5 12C23.5 5.65 18.35.5 12 .5z"/></svg>
                        </a>
                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-800 text-gray-400 transition-colors hover:bg-gray-700 hover:text-white" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 3.5h7.5A4.75 4.75 0 0120.5 8.25v7.5a4.75 4.75 0 01-4.75 4.75h-7.5A4.75 4.75 0 013.5 15.75v-7.5A4.75 4.75 0 018.25 3.5zM12 8.75a3.25 3.25 0 100 6.5 3.25 3.25 0 000-6.5zm4.5-.75a.9.9 0 11-1.8 0 .9.9 0 011.8 0z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Produk</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="#fitur" class="transition-colors hover:text-white">Fitur</a></li>
                        <li><a href="#cara-kerja" class="transition-colors hover:text-white">Cara Kerja</a></li>
                        <li><a href="{{ route('chat.index') }}" class="transition-colors hover:text-white">Chat</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Akun</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('login') }}" class="transition-colors hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="transition-colors hover:text-white">Daftar</a></li>
                        <li><a href="{{ route('dashboard') }}" class="transition-colors hover:text-white">Dashboard</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Dukungan</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="#faq" class="transition-colors hover:text-white">FAQ Umum</a></li>
                        <li><a href="#testimoni" class="transition-colors hover:text-white">Testimoni</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-gray-800 pt-6 text-xs text-gray-500 sm:flex-row">
                <p>&copy; 2026 Comsupport AI &mdash; Dibangun dengan Laravel &amp; Gemini AI.</p>
                <p>Ditenagai Google Gemini &middot; Knowledge base dikelola admin</p>
            </div>
        </div>
    </footer>

    <script>
        const menuButton = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>