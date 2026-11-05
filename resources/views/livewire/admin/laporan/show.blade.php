<div x-data="{
    confirmModal: false,
    targetStatus: '',
    modalTitle: '',
    modalMessage: '',
    confirmText: '',
    confirmTheme: 'wine',
    openConfirm(status, title, message, text, theme) {
        this.targetStatus = status;
        this.modalTitle = title;
        this.modalMessage = message;
        this.confirmText = text;
        this.confirmTheme = theme || 'wine';
        this.confirmModal = true;
    }
}">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">BERKAS PEMERIKSAAN RESMI</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Tinjau Pengaduan</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Periksa kronologi informasi dan tentukan eskalasi penanganan dinas terkait.</p>
        </div>
        <div class="flex items-center gap-2 print:hidden">
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center justify-center gap-2 rounded-md border border-[#4A151C] bg-[#4A151C] px-4 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white hover:bg-[#3C0F15] transition shadow-xs"
            >
                <i data-lucide="printer" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                Cetak Lembar Disposisi
            </button>
            <a
                href="{{ route('admin.laporan.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#554749] hover:bg-[#FAF7F5] transition"
            >
                <i data-lucide="arrow-left" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                Kembali ke Registratur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div role="status" class="mb-6 flex items-start gap-3 rounded-lg border border-[#B8D7C2] bg-[#F3F7F4] p-4 text-xs font-mono text-[#1D5E38]">
            <i data-lucide="circle-check" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#1D5E38]"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

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
                            class="print:hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-xs"
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
                            Tidak ada dokumen foto bukti dilampirkan oleh pelapor.
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

            {{-- Panel Tindak Lanjut --}}
            <section class="print:hidden rounded-lg border border-[#DCD5C8] bg-white p-6 shadow-xs">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">KONTROL OPERASIONAL</span>
                <h2 class="mt-1 font-serif text-lg font-bold text-[#1C1819]">Tindak Lanjut</h2>
                <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Perbarui status investigasi sesuai tahapan realisasi di lapangan.</p>

                @if($laporan->status === 'pending')
                    <button
                        type="button"
                        wire:loading.attr="disabled"
                        @click="openConfirm('proses', 'Mulai Proses Penanganan', 'Mulai proses penanganan pengaduan ini? Status akan diperbarui menjadi proses dan berkas dikunci.', 'Mulai Proses', 'wine')"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15] mt-4 w-full cursor-pointer"
                    >
                        Mulai Proses
                        <i data-lucide="arrow-right" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                    </button>
                @elseif($laporan->status === 'proses')
                    <button
                        type="button"
                        wire:loading.attr="disabled"
                        @click="openConfirm('selesai', 'Tandai Selesai Penanganan', 'Pastikan penanganan di lapangan telah tuntas sebelum menandai pengaduan ini sebagai selesai.', 'Tandai Selesai', 'emerald')"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-[#1D5E38] px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#16472A] mt-4 w-full cursor-pointer"
                    >
                        Tandai Selesai
                        <i data-lucide="check" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                    </button>
                @else
                    <div class="mt-4 flex items-center gap-2 rounded-md bg-[#F3F7F4] p-3 font-mono text-xs font-semibold text-[#1D5E38] border border-[#B8D7C2]">
                        <i data-lucide="circle-check" class="h-4 w-4 shrink-0"></i>
                        Pengaduan telah selesai ditangani.
                    </div>
                @endif

                <span wire:loading wire:target="changeStatus" role="status" class="mt-3 block font-mono text-xs text-[#4A151C]">Memperbarui status berkas…</span>
                @error('status')
                    <p role="alert" class="mt-3 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                @enderror
            </section>

            {{-- Kartu Ringkas Informasi Pelapor --}}
            <section class="rounded-lg border border-[#DCD5C8] bg-white p-6 shadow-xs">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">PROFIL SAKSI / PELAPOR</span>
                <h2 class="mt-1 mb-4 font-serif text-lg font-bold text-[#1C1819]">Informasi Pelapor</h2>
                <div class="space-y-3.5 font-mono text-xs">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#ECE7DF]">
                        @if($laporan->pelapor->fotoUrl())
                            <img src="{{ $laporan->pelapor->fotoUrl() }}" alt="{{ $laporan->pelapor->name }}" class="h-12 w-12 rounded-full object-cover border border-[#DCD5C8]">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#FAF7F5] border border-[#ECE7DF] font-serif text-lg font-bold text-[#4A151C]">
                                {{ mb_substr($laporan->pelapor->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <span class="block text-[#7A6B6E] text-[10px] uppercase tracking-wider">Identitas Saksi</span>
                            <span class="font-semibold text-[#1C1819] text-sm">{{ $laporan->pelapor->name }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-[#7A6B6E] text-[10px] uppercase tracking-wider">Email Terdaftar</span>
                        <span class="mt-0.5 block text-[#554749]">{{ $laporan->pelapor->email }}</span>
                    </div>
                    <div>
                        <span class="block text-[#7A6B6E] text-[10px] uppercase tracking-wider">Nomor WhatsApp</span>
                        @if($laporan->pelapor->no_whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $laporan->pelapor->no_whatsapp) }}" target="_blank" rel="noopener" class="mt-0.5 inline-flex items-center gap-1 font-semibold text-[#4A151C] hover:underline">
                                <span>{{ $laporan->pelapor->no_whatsapp }}</span>
                                <i data-lucide="external-link" class="h-3 w-3"></i>
                            </a>
                        @else
                            <span class="mt-0.5 block text-[#7A6B6E] italic">Belum diisi</span>
                        @endif
                    </div>
                    <div>
                        <span class="block text-[#7A6B6E] text-[10px] uppercase tracking-wider">Alamat Tinggal</span>
                        <p class="mt-0.5 leading-relaxed text-[#554749]">{{ $laporan->pelapor->alamat ?: 'Belum diisi' }}</p>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    {{-- Modal Konfirmasi Custom --}}
    <div
        x-show="confirmModal"
        x-cloak
        class="print:hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="confirmModal = false"
    >
        {{-- Backdrop --}}
        <div
            x-show="confirmModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="confirmModal = false"
            class="fixed inset-0 bg-[#1C1819]/60 backdrop-blur-xs transition-opacity"
        ></div>

        {{-- Dialog Panel --}}
        <div
            x-show="confirmModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
            class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 shadow-2xl transition-all border border-[#DCD5C8]"
        >
            <div class="flex items-start gap-4">
                <div
                    :class="confirmTheme === 'emerald' ? 'bg-[#F3F7F4] text-[#1D5E38] border border-[#B8D7C2]' : 'bg-[#FAF7F5] text-[#4A151C] border border-[#ECE7DF]'"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md"
                >
                    <i data-lucide="circle-alert" class="h-5 w-5"></i>
                </div>
                <div class="flex-1">
                    <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">KONFIRMASI TINDAKAN</span>
                    <h3 class="mt-0.5 font-serif text-lg font-bold text-[#1C1819]" x-text="modalTitle"></h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#554749]" x-text="modalMessage"></p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-[#ECE7DF] pt-5 font-mono text-xs">
                <button
                    type="button"
                    @click="confirmModal = false"
                    class="rounded-md border border-[#DCD5C8] bg-white px-4 py-2 font-bold uppercase tracking-wider text-[#554749] hover:bg-[#FAF7F5] transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    :class="confirmTheme === 'emerald' ? 'bg-[#1D5E38] hover:bg-[#16472A]' : 'bg-[#4A151C] hover:bg-[#3C0F15]'"
                    class="inline-flex items-center gap-2 rounded-md px-5 py-2 font-bold uppercase tracking-wider text-white shadow-xs transition cursor-pointer"
                    wire:loading.attr="disabled"
                    @click="$wire.changeStatus(targetStatus); confirmModal = false"
                >
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>