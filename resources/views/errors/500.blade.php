<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#4A151C">
        <title>Kendala Sistem · E-Report Layanan Sipil</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400..800;1,6..72,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @theme {
                --font-sans: 'Plus Jakarta Sans', sans-serif;
                --font-serif: 'Newsreader', serif;
                --font-mono: 'JetBrains Mono', monospace;
                --font-display: 'Bebas Neue', cursive;
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
        </style>
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="flex min-h-screen items-center justify-center bg-[#F7F4EE] px-6 font-sans text-[#1C1819]">
        <main class="max-w-md text-center">
            <div class="mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-display text-2xl tracking-wider text-[#4A151C]">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-[#4A151C] text-white">
                        <i data-lucide="server-crash" aria-hidden="true" class="h-5 w-5 shrink-0"></i>
                    </span>
                    <span class="tracking-widest">
                        E-REPORT<span class="text-[#C59B27]">.</span>
                    </span>
                </a>
            </div>
            <p class="font-display text-8xl font-bold tracking-widest text-[#DCD5C8]">500</p>
            <h1 class="mt-4 font-serif text-3xl font-bold text-[#1C1819]">Kendala Layanan Server</h1>
            <p class="mt-3 font-mono text-xs leading-relaxed text-[#7A6B6E]">Terjadi hambatan pemrosesan data internal pada server. Tim teknis telah menerima log anomali ini.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a
                    href="{{ auth()->check() ? route(auth()->user()->dashboardRoute()) : route('home') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-6 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15]"
                >
                    Kembali ke Beranda
                </a>
            </div>
        </main>
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
