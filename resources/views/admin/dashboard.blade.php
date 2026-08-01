<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900">Dashboard Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div
                class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 p-8 text-white shadow-xl">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <p class="text-blue-200 text-sm font-medium">Selamat datang kembali,</p>
                    <h2 class="mt-1 text-2xl font-bold">{{ auth()->user()->name }}</h2>
                    <p class="mt-2 text-sm text-blue-100/80">Kelola FAQ dan pantau chatbot kamu dari sini.</p>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Users</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Menu Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('admin.faqs.index') }}"
                        class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-200">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Kelola FAQ</p>
                            <p class="text-xs text-gray-500">Tambah, edit, hapus data FAQ</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
