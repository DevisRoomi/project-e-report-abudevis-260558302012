<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">PORTAL ADUAN SAYA</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Daftar Pengaduan Anda</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Pantau seluruh arsip laporan aspirasi dan perkembangan investigasi lapangan secara berkala.</p>
        </div>
        <a
            href="{{ route('warga.laporan.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15]"
        >
            <i data-lucide="plus" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
            Buat Pengaduan Baru
        </a>
    </div>

    @if(session('success'))
        <div role="status" class="mb-6 flex items-start gap-3 rounded-lg border border-[#B8D7C2] bg-[#F3F7F4] p-4 text-xs font-mono text-[#1D5E38]">
            <i data-lucide="circle-check" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#1D5E38]"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Pencarian dan filter pengaduan --}}
    <div class="mb-6 flex flex-col gap-3 rounded-lg border border-[#DCD5C8] bg-white p-4 sm:flex-row shadow-xs">
        <div class="relative flex-1">
            <label for="search" class="sr-only">Cari pengaduan</label>
            <span class="pointer-events-none absolute top-3 left-3 text-[#7A6B6E]">
                <i data-lucide="search" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
            </span>
            <input
                id="search"
                type="search"
                wire:model.live.debounce.350ms="search"
                placeholder="Cari pokok judul pengaduan…"
                class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] py-2.5 pr-4 pl-9 text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C]"
            >
        </div>
        <div>
            <label for="status" class="sr-only">Filter status</label>
            <select id="status" wire:model.live="status" class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-3.5 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white sm:w-48">
                <option value="">Semua Status Berkas</option>
                @foreach(\App\Models\Laporan::STATUSES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <span wire:loading role="status" class="self-center font-mono text-xs text-[#4A151C]">Memuat…</span>
    </div>

    {{-- Tabel pengaduan dan pagination --}}
    <div class="overflow-hidden rounded-lg border border-[#DCD5C8] bg-white shadow-xs">
        @if($laporans->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-[#FAF7F5] border border-[#ECE7DF] text-[#7A6B6E]">
                    <i data-lucide="inbox" aria-hidden="true" class="h-6 w-6 shrink-0"></i>
                </div>
                <h3 class="font-serif text-lg font-bold text-[#1C1819]">Belum Ada Pengaduan Ditemukan</h3>
                <p class="mx-auto mt-1 max-w-sm text-xs text-[#7A6B6E]">Sesuaikan kata kunci pencarian atau buat pengaduan baru bila ada kendala fasilitas di sekitar.</p>
                <div class="mt-6">
                    <a
                        href="{{ route('warga.laporan.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15]"
                    >
                        <i data-lucide="plus" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                        Buat Pengaduan Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-xs">
                    <thead class="border-b border-[#ECE7DF] bg-[#FAF7F5] font-mono text-[10px] uppercase tracking-wider text-[#7A6B6E]">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">ID / Pokok Masalah</th>
                            <th class="px-4 py-3.5 font-semibold">Tanggal Kejadian</th>
                            <th class="px-4 py-3.5 font-semibold">Tanggal Berkas</th>
                            <th class="px-4 py-3.5 font-semibold">Status Penanganan</th>
                            <th class="px-6 py-3.5 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ECE7DF]">
                        @foreach($laporans as $laporan)
                            <tr wire:key="laporan-{{ $laporan->id }}" class="hover:bg-[#FAF7F5]/80 transition">
                                <td class="max-w-xs px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($laporan->fotoUrl())
                                            <img src="{{ $laporan->fotoUrl() }}" alt="Thumbnail {{ $laporan->judul }}" class="h-10 w-10 shrink-0 rounded-md object-cover border border-[#DCD5C8]">
                                        @else
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-[#ECE7DF] bg-[#FAF7F5] text-[#7A6B6E]">
                                                <i data-lucide="image" aria-hidden="true" class="h-4 w-4"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <p class="font-mono text-[10px] uppercase tracking-wider text-[#7A6B6E]">#{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</p>
                                            <a
                                                href="{{ route('warga.laporan.show', $laporan) }}"
                                                class="mt-0.5 block truncate font-semibold text-[#1C1819] hover:text-[#4A151C] transition"
                                            >{{ $laporan->judul }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 font-mono text-[11px] text-[#554749]">{{ $laporan->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4 font-mono text-[11px] text-[#7A6B6E]">{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    <x-badge-status :status="$laporan->status" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a
                                            href="{{ route('warga.laporan.show', $laporan) }}"
                                            aria-label="Detail {{ $laporan->judul }}"
                                            title="Buka Berkas"
                                            class="rounded border border-[#DCD5C8] bg-white p-1.5 text-[#554749] hover:border-[#4A151C] hover:bg-[#FAF7F5] hover:text-[#4A151C] transition"
                                        >
                                            <i data-lucide="arrow-up-right" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                                        </a>
                                        @can('update', $laporan)
                                            <a
                                                href="{{ route('warga.laporan.edit', $laporan) }}"
                                                aria-label="Edit {{ $laporan->judul }}"
                                                title="Sunting Berkas"
                                                class="rounded border border-[#DCD5C8] bg-white p-1.5 text-[#554749] hover:border-[#C59B27] hover:bg-[#FAF7F5] hover:text-[#8C6D0F] transition"
                                            >
                                                <i data-lucide="pencil" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                                            </a>
                                            <button
                                                wire:click="delete({{ $laporan->id }})"
                                                wire:confirm="Hapus pengaduan ini? Foto bukti juga akan dihapus. Tindakan ini tidak dapat dibatalkan."
                                                wire:loading.attr="disabled"
                                                aria-label="Hapus {{ $laporan->judul }}"
                                                title="Cabut Berkas"
                                                class="rounded border border-[#DCD5C8] bg-white p-1.5 text-[#554749] hover:border-[#85222E] hover:bg-[#FAF7F5] hover:text-[#85222E] transition"
                                            >
                                                <i data-lucide="trash-2" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#ECE7DF] bg-[#FAF7F5] px-6 py-3.5">
                @if($laporans->hasPages())
                    <nav role="navigation" aria-label="Halaman pengaduan" class="flex flex-wrap items-center justify-between gap-4 font-mono text-xs">
                        <p class="text-[#7A6B6E]">Menampilkan {{ $laporans->firstItem() }} – {{ $laporans->lastItem() }} dari {{ $laporans->total() }} berkas</p>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="previousPage('{{ $laporans->getPageName() }}')"
                                wire:loading.attr="disabled"
                                @disabled($laporans->onFirstPage())
                                class="rounded border border-[#DCD5C8] bg-white px-3 py-1.5 text-[#1C1819] disabled:opacity-40 hover:bg-[#FAF7F5]"
                            >Sebelumnya</button>
                            <span class="font-bold text-[#4A151C]">{{ $laporans->currentPage() }} / {{ $laporans->lastPage() }}</span>
                            <button
                                type="button"
                                wire:click="nextPage('{{ $laporans->getPageName() }}')"
                                wire:loading.attr="disabled"
                                @disabled(!$laporans->hasMorePages())
                                class="rounded border border-[#DCD5C8] bg-white px-3 py-1.5 text-[#1C1819] disabled:opacity-40 hover:bg-[#FAF7F5]"
                            >Berikutnya</button>
                        </div>
                    </nav>
                @else
                    <p class="font-mono text-xs text-[#7A6B6E]">{{ $laporans->total() }} total pengaduan terdaftar</p>
                @endif
            </div>
        @endif
    </div>
</div>