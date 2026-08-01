<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition">Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('admin.faqs.index') }}" class="text-gray-500 hover:text-gray-700 transition">FAQ</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-800 font-medium">Edit</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('admin.faqs.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition group">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar FAQ
                </a>
                <h1 class="mt-3 text-2xl font-bold text-gray-900">Edit FAQ</h1>
                <p class="mt-1 text-sm text-gray-500">Perbarui pertanyaan dan jawaban FAQ #{{ $faq->id }}.</p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-amber-400 via-orange-500 to-red-500"></div>

                <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Pertanyaan --}}
                    <div>
                        <label for="question" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 rounded-md text-xs font-bold">Q</span>
                            Pertanyaan
                        </label>
                        <input type="text" name="question" id="question"
                               value="{{ old('question', $faq->question) }}"
                               placeholder="Tulis pertanyaan FAQ..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-shadow @error('question') border-red-300 ring-2 ring-red-500/20 @enderror"
                               required />
                        @error('question')
                            <p class="flex items-center gap-1.5 text-red-600 text-xs mt-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jawaban --}}
                    <div>
                        <label for="answer" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-600 rounded-md text-xs font-bold">A</span>
                            Jawaban
                        </label>
                        <textarea name="answer" id="answer" rows="5"
                                  placeholder="Tulis jawaban yang jelas dan lengkap..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-shadow resize-y @error('answer') border-red-300 ring-2 ring-red-500/20 @enderror"
                                  required>{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <p class="flex items-center gap-1.5 text-red-600 text-xs mt-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="category" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-600 rounded-md text-xs font-bold">#</span>
                            Kategori
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" name="category" id="category"
                               value="{{ old('category', $faq->category) }}"
                               placeholder="Contoh: Pengiriman, Pembayaran, Akun"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-shadow" />
                    </div>

                    {{-- Info Terakhir Diupdate --}}
                    <div class="flex items-center gap-2 text-xs text-gray-400 bg-gray-50 px-4 py-3 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Terakhir diubah: {{ $faq->updated_at->diffForHumans() }}
                    </div>

                    {{-- Divider & Submit --}}
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.faqs.index') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Perbarui FAQ
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
