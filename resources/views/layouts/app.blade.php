<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#4A151C">
        <title>{{ $title ?? 'Dashboard' }} · E-Report Layanan Sipil</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;0,6..72,700;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @theme {
                --font-display: 'Bebas Neue', 'Plus Jakarta Sans', sans-serif;
                --font-serif: 'Newsreader', serif;
                --font-sans: 'Plus Jakarta Sans', sans-serif;
                --font-mono: 'JetBrains Mono', monospace;
            }

            @layer base {
                button, a, input, textarea, select {
                    @apply focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#4A151C];
                }

                button:not(:disabled), select {
                    @apply cursor-pointer;
                }

                button:disabled {
                    @apply cursor-wait opacity-60;
                }
            }

            @media print {
                body {
                    background: #ffffff !important;
                    color: #000000 !important;
                }
                main {
                    padding: 0 !important;
                    margin: 0 !important;
                    max-width: 100% !important;
                }
                .shadow-xs, .shadow-sm, .shadow {
                    box-shadow: none !important;
                }
            }
        </style>
        <style>[x-cloak] { display: none !important; }</style>
        @livewireStyles
    </head>
    <body class="bg-[#F7F4EE] font-sans text-[#1C1819] antialiased" x-data="{ menuOpen: false, desktop: window.innerWidth >= 1024, scrollProgress: 0 }" @scroll.window="const h = document.documentElement.scrollHeight - window.innerHeight; scrollProgress = h > 0 ? (window.scrollY / h) * 100 : 0" @resize.window="desktop = window.innerWidth >= 1024" @keydown.escape.window="menuOpen = false">
        {{-- Offline Alert Bar --}}
        <div wire:offline class="print:hidden fixed top-0 inset-x-0 z-50 flex items-center justify-center gap-2 bg-[#85222E] px-4 py-2 font-mono text-xs font-semibold text-white shadow-md">
            <i data-lucide="wifi-off" class="h-4 w-4 shrink-0"></i>
            <span>Koneksi internet terputus. Sistem menunggu jaringan kembali pulih…</span>
        </div>

        <div class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#C59B27] via-[#85222E] to-[#4A151C] z-50 transition-all duration-75" :style="`width: ${scrollProgress}%`"></div>
        <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-xl focus:bg-[#4A151C] focus:text-white focus:p-3 focus:font-mono focus:text-xs">Langsung ke konten</a>
        <div x-show="menuOpen" x-cloak @click="menuOpen = false" class="fixed inset-0 z-30 bg-[#1C1819]/60 backdrop-blur-xs lg:hidden"></div>

        {{-- Sidebar: deep oxblood / wine aesthetic --}}
        <aside :inert="!menuOpen && !desktop" :class="menuOpen ? 'translate-x-0' : '-translate-x-full'" class="print:hidden fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-[#310A10] bg-[#3C0F15] text-[#FAF7F5] transition-transform duration-200 lg:translate-x-0">
            <div class="flex h-20 shrink-0 items-center justify-between border-b border-[#4A151C] px-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <x-civic-logo size="sm" variant="white" />
                    <div>
                        <span class="font-display text-2xl tracking-wider text-white">E-REPORT<span class="text-[#C59B27]">.</span></span>
                        <span class="block font-mono text-[9px] uppercase tracking-widest text-[#E0D0D2]">Layanan Sipil</span>
                    </div>
                </a>
                <button @click="menuOpen = false" class="text-[#E0D0D2] hover:text-white lg:hidden" aria-label="Tutup navigasi">
                    <i data-lucide="x" aria-hidden="true" class="h-5 w-5 shrink-0"></i>
                </button>
            </div>

            <div class="px-3 pt-6 flex-1 overflow-y-auto">
                <p class="mb-3 px-3 font-mono text-[10px] font-bold tracking-[.18em] uppercase text-[#B99A9F]">RUANG {{ auth()->user()->role === 'admin' ? 'ADMINISTRATOR' : 'WARGA' }}</p>
                <nav aria-label="Navigasi utama" class="space-y-1">
                    @if(auth()->user()->role === 'admin')
                        <a
                            href="{{ route('admin.dashboard') }}"
                            aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold tracking-wide transition-all duration-150 active:scale-95 cursor-pointer',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('admin.dashboard'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white hover:translate-x-0.5' => ! request()->routeIs('admin.dashboard'),
                            ])
                        >
                            <i data-lucide="layout-dashboard" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Dashboard Antrean
                        </a>
                        <a
                            href="{{ route('admin.laporan.index') }}"
                            aria-current="{{ request()->routeIs('admin.laporan.*') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold tracking-wide transition-all duration-150 active:scale-95 cursor-pointer',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('admin.laporan.*'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white' => ! request()->routeIs('admin.laporan.*'),
                            ])
                        >
                            <i data-lucide="folder-kanban" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Semua Pengaduan
                        </a>
                    @else
                        <a
                            href="{{ route('warga.dashboard') }}"
                            aria-current="{{ request()->routeIs('warga.dashboard') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-md px-3.5 py-2.5 text-xs font-semibold tracking-wide transition',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('warga.dashboard'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white' => ! request()->routeIs('warga.dashboard'),
                            ])
                        >
                            <i data-lucide="layout-dashboard" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Dashboard Warga
                        </a>
                        <a
                            href="{{ route('warga.laporan.index') }}"
                            aria-current="{{ request()->routeIs('warga.laporan.index', 'warga.laporan.show', 'warga.laporan.edit') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-md px-3.5 py-2.5 text-xs font-semibold tracking-wide transition',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('warga.laporan.index', 'warga.laporan.show', 'warga.laporan.edit'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white' => ! request()->routeIs('warga.laporan.index', 'warga.laporan.show', 'warga.laporan.edit'),
                            ])
                        >
                            <i data-lucide="archive" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Pengaduan Saya
                        </a>
                        <a
                            href="{{ route('warga.laporan.create') }}"
                            aria-current="{{ request()->routeIs('warga.laporan.create') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-md px-3.5 py-2.5 text-xs font-semibold tracking-wide transition',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('warga.laporan.create'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white' => ! request()->routeIs('warga.laporan.create'),
                            ])
                        >
                            <i data-lucide="plus-circle" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Buat Pengaduan Baru
                        </a>
                        <a
                            href="{{ route('warga.profile') }}"
                            aria-current="{{ request()->routeIs('warga.profile') ? 'page' : 'false' }}"
                            @class([
                                'flex items-center gap-3 rounded-md px-3.5 py-2.5 text-xs font-semibold tracking-wide transition',
                                'bg-white text-[#3C0F15] shadow-xs' => request()->routeIs('warga.profile'),
                                'text-[#E0D0D2] hover:bg-[#4A151C] hover:text-white' => ! request()->routeIs('warga.profile'),
                            ])
                        >
                            <i data-lucide="user" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                            Profil & Data Warga
                        </a>
                    @endif
                </nav>
            </div>

            <div class="m-4 rounded-md border border-[#521820] bg-[#330C12] p-3.5 text-xs">
                <div class="flex items-center gap-2 text-white font-serif text-sm font-bold">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Transparansi Publik
                </div>
                <p class="mt-1 text-[11px] leading-relaxed text-[#D6C4C6]">Setiap aduan warga diverifikasi dan ditindaklanjuti secara akuntabel.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-[#4A151C] p-3">
                @csrf
                <button class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-xs font-medium text-[#E0D0D2] transition hover:bg-[#4A151C] hover:text-white">
                    <i data-lucide="log-out" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                    Keluar Sesi
                </button>
            </form>
        </aside>

        {{-- Main viewport wrapper --}}
        <div class="lg:pl-64 print:pl-0">
            <header class="print:hidden sticky top-0 z-20 flex h-18 items-center justify-between gap-4 border-b border-[#E5DBD9] bg-white/95 px-5 backdrop-blur-xs sm:px-8">
                <div class="flex items-center gap-3">
                    <button @click="menuOpen = true" aria-label="Buka navigasi" :aria-expanded="menuOpen" class="rounded-md border border-[#E5DBD9] p-2 text-[#4A151C] hover:bg-[#F7F4F2] lg:hidden">
                        <i data-lucide="menu" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                    </button>
                    <div>
                        <p class="font-serif text-base font-bold text-[#1C1819]">{{ $title ?? 'Dashboard' }}</p>
                        <p class="hidden font-mono text-[10px] uppercase tracking-wider text-[#7A6B6E] sm:block">Sistem Informasi Pengaduan & Layanan Publik</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden items-center gap-2 rounded-md border border-[#E5DBD9] bg-[#FAF7F5] px-3 py-1.5 font-mono text-[11px] text-[#554749] md:flex">
                        <i data-lucide="calendar" aria-hidden="true" class="h-3.5 w-3.5 text-[#4A151C]"></i>
                        <span>{{ now()->locale('id')->translatedFormat('d F Y') }}</span>
                    </div>

                    <div class="flex items-center gap-3 border-l border-[#E5DBD9] pl-4">
                        @if(auth()->user()->fotoUrl())
                            <img src="{{ auth()->user()->fotoUrl() }}" alt="Foto profil" class="h-9 w-9 rounded-md object-cover ring-1 ring-[#4A151C]/20">
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-md bg-[#4A151C] font-mono text-xs font-bold text-white shadow-2xs">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        @endif
                        <div class="hidden text-left sm:block">
                            <p class="max-w-xs truncate text-xs font-bold text-[#1C1819]" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</p>
                            <span class="inline-block font-mono text-[10px] uppercase tracking-wider text-[#4A151C] font-semibold">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Warga Terdaftar' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main id="main-content" class="mx-auto min-h-[calc(100vh-140px)] max-w-7xl px-4 py-8 sm:px-8 sm:py-10">
                {{-- Global Toast Alerts --}}
                @if(session('success'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        role="status"
                        class="mb-6 flex items-center justify-between rounded-lg border border-[#B8D7C2] bg-[#F3F7F4] p-4 text-xs font-mono text-[#1D5E38] shadow-xs"
                    >
                        <div class="flex items-center gap-3">
                            <i data-lucide="circle-check" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#1D5E38]"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="text-[#1D5E38] hover:opacity-75">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 7000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        role="alert"
                        class="mb-6 flex items-center justify-between rounded-lg border border-[#E8B4B8] bg-[#FDF2F2] p-4 text-xs font-mono text-[#85222E] shadow-xs"
                    >
                        <div class="flex items-center gap-3">
                            <i data-lucide="alert-circle" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#85222E]"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" @click="show = false" aria-label="Tutup notifikasi" class="text-[#85222E] hover:opacity-75">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <footer class="print:hidden flex flex-wrap items-center justify-between gap-3 border-t border-[#E5DBD9] bg-white px-5 py-4 font-mono text-[11px] text-[#7A6B6E] sm:px-8">
                <p>&copy; {{ date('Y') }} E-Report Layanan Sipil.</p>
                <div class="flex items-center gap-3">
                    <span>Sistem Akuntabilitas Publik</span>
                    <span>&middot;</span>
                    <a href="{{ route('home') }}" class="hover:text-[#4A151C] underline underline-offset-2">Beranda Publik</a>
                </div>
            </footer>
        </div>

        @livewireScripts
        <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
        <script>
            function renderIcons() {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }

            renderIcons();
            document.addEventListener('livewire:navigated', renderIcons);
            document.addEventListener('livewire:init', () => {
                Livewire.hook('morphed', renderIcons);
            });
        </script>
    </body>
</html>