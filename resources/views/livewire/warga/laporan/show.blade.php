<div wire:poll.15s>
    @if(session('success'))
        <div role="status" class="mb-6 flex items-start gap-3 rounded-lg border border-[#B8D7C2] bg-[#F3F7F4] p-4 text-xs font-mono text-[#1D5E38]">
            <i data-lucide="circle-check" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#1D5E38]"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">STATUS & KRONOLOGI PENANGANAN</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Detail Pengaduan</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Pantau tahapan tindak lanjut dan arsip berkas pengaduan Anda.</p>
        </div>
        <a
            href="{{ route('warga.laporan.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-md border border-[#DCD5C8] bg-white px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#554749] hover:bg-[#FAF7F5] transition"
        >
            <i data-lucide="arrow-left" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
            Kembali ke Berkas Saya
        </a>
    </div>

    <div class="grid items-start gap-6 lg:grid-cols-[1fr_320px]">
        {{-- Informasi lengkap pengaduan --}}
        <article class="overflow-hidden rounded-lg border border-[#DCD5C8] bg-white shadow-xs">
            <div class="border-b border-[#ECE7DF] p-6 sm:p-8">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <span class="font-mono text-xs font-bold tracking-wider text-[#7A6B6E]">BERKAS #{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <x-badge-status :status="$laporan->status" />
                </div>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold leading-snug break-words text-[#1C1819]">{{ $laporan->judul }}</h2>
                <div class="mt-6 grid gap-4 font-mono text-xs text-[#7A6B6E] sm:grid-cols-2 border-t border-[#ECE7DF] pt-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" aria-hidden="true" class="h-3.5 w-3.5 shrink-0 text-[#C59B27]"></i>
                        <span>
                            Kejadian:
                            <strong class="font-semibold text-[#1C1819]">{{ $laporan->tanggal->translatedFormat('d F Y') }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock-3" aria-hidden="true" class="h-3.5 w-3.5 shrink-0 text-[#C59B27]"></i>
                        <span>Diajukan: {{ $laporan->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="p-6 sm:p-8">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">KRONOLOGI / POKOK PERKARA</span>
                <h3 class="mt-1 mb-4 font-serif text-lg font-bold text-[#1C1819]">Deskripsi Pengaduan</h3>
                <p class="text-xs leading-relaxed break-words whitespace-pre-line text-[#554749]">{{ $laporan->detail }}</p>

                <div class="mt-8 border-t border-[#ECE7DF] pt-6" x-data="{ lightbox: false }">
                    <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">DOKUMEN LAMPIRAN</span>
                    <h3 class="mt-1 mb-4 font-serif text-lg font-bold text-[#1C1819]">Foto Bukti Lapangan</h3>
                    @if($laporan->fotoUrl())
                        <div class="relative group cursor-pointer" @click="lightbox = true">
                            <img src="{{ $laporan->fotoUrl() }}" alt="Foto bukti: {{ $laporan->judul }}" class="max-h-[500px] w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] object-contain">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition rounded-md">
                                <span class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-1.5 font-mono text-xs font-bold text-[#4A151C] shadow-md">
                                    <i data-lucide="maximize-2" class="h-3.5 w-3.5"></i>
                                    Perbesar Tampilan
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 font-mono text-[11px] text-[#7A6B6E]">Klik foto untuk memperbesar pratinjau resolusi penuh.</p>

                        {{-- Lightbox Modal --}}
                        <div
                            x-show="lightbox"
                            x-cloak
                            @keydown.escape.window="lightbox = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-xs"
                        >
                            <div class="relative max-h-screen max-w-5xl" @click.away="lightbox = false">
                                <button
                                    type="button"
                                    @click="lightbox = false"
                                    class="absolute -top-10 right-0 flex items-center gap-1 font-mono text-xs text-white hover:text-[#C59B27]"
                                >
                                    <i data-lucide="x" class="h-4 w-4"></i>
                                    Tutup (ESC)
                                </button>
                                <img src="{{ $laporan->fotoUrl() }}" alt="Foto bukti resolusi penuh" class="max-h-[85vh] w-auto rounded border border-white/20 object-contain shadow-2xl">
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center rounded-md border border-dashed border-[#DCD5C8] bg-[#FAF7F5] p-10 text-center font-mono text-xs text-[#7A6B6E]">
                            <i data-lucide="image" aria-hidden="true" class="h-6 w-6 shrink-0 text-[#C59B27] mb-2"></i>
                            Tidak ada dokumen foto bukti dilampirkan.
                        </div>
                    @endif
                </div>
            </div>
        </article>

        <aside class="space-y-6">
            {{-- Tahapan alur progres bergaris (stepper) --}}
            <section class="rounded-lg border border-[#DCD5C8] bg-white p-6 shadow-xs">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">TRACKING RESMI</span>
                <h2 class="mt-1 font-serif text-lg font-bold text-[#1C1819]">Progres Pengaduan</h2>
                <div class="relative mt-6 space-y-6 before:absolute before:left-[17px] before:top-3 before:bottom-3 before:w-px before:bg-[#DCD5C8]">
                    @foreach(['pending' => ['clock-3', 'Menunggu Peninjauan'], 'proses' => ['loader-circle', 'Sedang Diproses'], 'selesai' => ['circle-check', 'Selesai Ditangani']] as $status => [$icon, $label])
                        @php
                            $order = ['pending', 'proses', 'selesai'];
                            $currentIdx = array_search($laporan->status, $order);
                            $stepIdx = array_search($status, $order);
                            $isCurrent = $laporan->status === $status;
                            $isDone = $currentIdx > $stepIdx;
                        @endphp
                        <div class="relative flex items-center gap-3.5">
                            <span @class([
                                'relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full ring-4 ring-white transition-all text-xs font-mono',
                                'bg-[#4A151C] text-white shadow-xs' => $isCurrent,
                                'bg-[#F3F7F4] text-[#1D5E38] ring-[#B8D7C2]' => $isDone,
                                'bg-[#FAF7F5] text-[#7A6B6E] border border-[#ECE7DF]' => !$isCurrent && !$isDone,
                            ])>
                                <i data-lucide="{{ $isDone ? 'check' : $icon }}" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                            </span>
                            <div>
                                <p @class([
                                    'text-xs font-mono',
                                    'font-bold text-[#1C1819]' => $isCurrent,
                                    'font-medium text-[#554749]' => $isDone,
                                    'text-[#7A6B6E]' => !$isCurrent && !$isDone,
                                ])>
                                    {{ $label }}
                                </p>
                                @if($isCurrent)
                                    <span class="mt-0.5 inline-block font-mono text-[10px] font-bold uppercase tracking-wider text-[#C59B27]">Tahap saat ini</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-6 rounded-md bg-[#FAF7F5] p-3 font-mono text-[11px] leading-relaxed text-[#7A6B6E] border border-[#ECE7DF]">{{ $laporan->statusDescription() }}</p>
            </section>

            @can('update', $laporan)
                <section class="rounded-lg border border-[#DCD5C8] bg-white p-6 shadow-xs">
                    <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">TINDAKAN BERKAS</span>
                    <h2 class="mt-1 font-serif text-lg font-bold text-[#1C1819]">Kelola Pengaduan</h2>
                    <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Perubahan data diperbolehkan selama pengaduan masih berstatus pending.</p>
                    <a
                        href="{{ route('warga.laporan.edit', $laporan) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-[#DCD5C8] bg-white px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819] hover:bg-[#FAF7F5] transition mt-4 w-full"
                    >
                        <i data-lucide="pencil" aria-hidden="true" class="h-3.5 w-3.5 shrink-0 text-[#C59B27]"></i>
                        Edit Pengaduan
                    </a>
                    <button
                        type="button"
                        wire:loading.attr="disabled"
                        wire:click="delete"
                        wire:confirm="Hapus pengaduan beserta foto buktinya? Tindakan ini tidak dapat dibatalkan."
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-[#F1C0C7] bg-[#FDF2F4] px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#85222E] hover:bg-[#F9DEE3] transition mt-3 w-full cursor-pointer"
                    >
                        <i data-lucide="trash-2" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                        Hapus Pengaduan
                    </button>
                </section>
            @endcan
        </aside>
    </div>
</div>