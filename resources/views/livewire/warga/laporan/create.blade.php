<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">REGISTRASI FORMULIR LAPORAN</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Buat Pengaduan Baru</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Sampaikan permasalahan fasilitas lingkungan dengan detail yang jelas dan akurat.</p>
        </div>
    </div>

    <div class="grid items-start gap-6 lg:grid-cols-[1fr_320px]">
        <form wire:submit="save" class="rounded-lg border border-[#DCD5C8] bg-white p-6 sm:p-8 shadow-xs">
            <div class="mb-6 border-b border-[#ECE7DF] pb-4">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">DOKUMEN PENGADUAN</span>
                <h2 class="mt-1 font-serif text-xl font-bold text-[#1C1819]">Informasi Pengaduan</h2>
            </div>

            {{-- Formulir pengaduan: setiap input terhubung ke properti Livewire --}}
            <div class="space-y-6">
                <div x-data="{ count: $wire.entangle('judul').live ? $wire.judul.length : ($wire.get('judul') || '').length }">
                    <div class="mb-2 flex items-center justify-between">
                        <label for="judul" class="block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Judul pengaduan</label>
                        <span class="font-mono text-[10px] text-[#7A6B6E]" x-text="($wire.judul ? $wire.judul.length : 0) + ' / 255'"></span>
                    </div>
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        aria-describedby="judul-help judul-error"
                        @error('judul')
                        aria-invalid="true"
                        @enderror
                        wire:model.live="judul"
                        placeholder="Contoh: Lampu penerangan jalan padam di Jl. Merpati RW 04"
                        maxlength="255"
                        required
                        class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-4 py-3 text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C]"
                    >
                    <p id="judul-help" class="mt-1.5 text-[11px] leading-relaxed text-[#7A6B6E]">Gunakan judul ringkas dan representatif terhadap masalah utama.</p>
                    @error('judul')
                        <p id="judul-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label for="detail" class="block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Deskripsi pengaduan</label>
                        <span class="font-mono text-[10px] text-[#7A6B6E]" x-text="($wire.detail ? $wire.detail.length : 0) + ' / 10.000'"></span>
                    </div>
                    <textarea
                        id="detail"
                        name="detail"
                        aria-describedby="detail-help detail-error"
                        @error('detail')
                        aria-invalid="true"
                        @enderror
                        wire:model.live="detail"
                        rows="6"
                        placeholder="Uraikan kronologi, titik lokasi spesifik (patokan/koordinat), dan dampak terhadap kenyamanan atau keselamatan warga…"
                        maxlength="10000"
                        required
                        class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-4 py-3 text-xs leading-relaxed text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C] min-h-32"
                    ></textarea>
                    <p id="detail-help" class="mt-1.5 text-[11px] leading-relaxed text-[#7A6B6E]">Sertakan patokan lokasi kejadian secara detail. Maksimal 10.000 karakter.</p>
                    @error('detail')
                        <p id="detail-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal" class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Tanggal kejadian</label>
                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        aria-describedby="tanggal-error"
                        @error('tanggal')
                        aria-invalid="true"
                        @enderror
                        wire:model="tanggal"
                        max="{{ today()->format('Y-m-d') }}"
                        required
                        class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C]"
                    >
                    @error('tanggal')
                        <p id="tanggal-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                    @enderror
                </div>
                <div class="rounded-lg border border-[#DCD5C8] bg-[#FAF7F5] p-5">
                    <div
                        x-data="{ uploading: false, progress: 0 }"
                        x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false; progress = 0"
                        x-on:livewire-upload-error="uploading = false; progress = 0"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <label for="foto" class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Foto bukti lapangan (opsional)</label>
                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            aria-describedby="foto-help foto-error"
                            @error('foto')
                            aria-invalid="true"
                            @enderror
                            wire:model="foto"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-3 py-2 text-xs font-mono text-[#554749] outline-none file:mr-3 file:rounded file:border-0 file:bg-[#4A151C] file:px-3 file:py-1.5 file:font-mono file:text-xs file:font-bold file:uppercase file:tracking-wider file:text-white hover:file:bg-[#3C0F15]"
                        >
                        <div x-show="uploading" class="mt-2 w-full bg-[#ECE7DF] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[#4A151C] h-1.5 rounded-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                        </div>
                        <p x-show="uploading" class="mt-1 font-mono text-[11px] text-[#4A151C]" x-text="'Mengunggah bukti: ' + progress + '%'"></p>
                        <p id="foto-help" class="mt-2 text-[11px] leading-relaxed text-[#7A6B6E]">Format JPG, PNG, atau WebP. Maksimal ukuran 5 MB. Lampirkan foto yang memperjelas kondisi fisik.</p>
                        @error('foto')
                            <p id="foto-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror

                        @if ($foto && method_exists($foto, 'temporaryUrl') && method_exists($foto, 'isPreviewable') && $foto->isPreviewable())
                            <div class="mt-4 rounded-md border border-[#DCD5C8] bg-white p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <p class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#4A151C]">Pratinjau Foto Bukti:</p>
                                    <button
                                        type="button"
                                        wire:click="batalPilihFoto"
                                        class="inline-flex items-center gap-1 rounded border border-[#DCD5C8] bg-[#FAF7F5] px-2 py-0.5 font-mono text-[10px] font-bold text-[#85222E] hover:bg-white transition"
                                    >
                                        <i data-lucide="x" class="h-3 w-3"></i>
                                        Batal Unggah
                                    </button>
                                </div>
                                <img src="{{ $foto->temporaryUrl() }}" alt="Pratinjau bukti pengaduan" class="max-h-56 rounded border border-[#ECE7DF] object-contain">
                            </div>
                        @endif
                    </div>
                    <p wire:loading wire:target="foto" role="status" class="mt-3 font-mono text-xs text-[#4A151C]">Mengunggah berkas foto…</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-[#ECE7DF] pt-6">
                <a
                    href="{{ route('warga.laporan.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-[#DCD5C8] bg-white px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#554749] hover:bg-[#FAF7F5] transition"
                >Batal</a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save,foto"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-6 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15]"
                >
                    <span wire:loading.remove wire:target="save">Kirim Pengaduan</span>
                    <span wire:loading wire:target="save">Mengirimkan…</span>
                    <i data-lucide="send" aria-hidden="true" class="h-3.5 w-3.5 shrink-0"></i>
                </button>
            </div>
        </form>

        <aside class="rounded-lg border border-[#DCD5C8] bg-white p-6 shadow-xs">
            <span class="inline-flex rounded p-2 bg-[#FAF7F5] border border-[#ECE7DF] text-[#C59B27] mb-4">
                <i data-lucide="compass" aria-hidden="true" class="h-5 w-5 shrink-0"></i>
            </span>
            <span class="block font-mono text-[10px] font-bold tracking-[.15em] uppercase text-[#C59B27]">PANDUAN VERIFIKASI</span>
            <h2 class="mt-1 font-serif text-lg font-bold text-[#1C1819]">Kriteria Laporan Efektif</h2>
            <ul class="mt-4 space-y-3 font-mono text-xs leading-relaxed text-[#554749]">
                <li class="flex items-start gap-2">
                    <span class="text-[#C59B27]">•</span>
                    <span>Tuliskan alamat detail atau patokan lokasi nyata di lapangan.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#C59B27]">•</span>
                    <span>Sertakan foto beresolusi jelas bila memungkinkan.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#C59B27]">•</span>
                    <span>Gunakan bahasa lugas, santun, dan objektif.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-[#C59B27]">•</span>
                    <span>Hindari data sensitif identitas pihak ketiga.</span>
                </li>
            </ul>
            <p class="mt-6 border-t border-[#ECE7DF] pt-4 font-mono text-[11px] text-[#7A6B6E]">Status laporan langsung tercatat <span class="text-[#8C6D0F] font-bold">MENUNGGU</span> setelah dikirim.</p>
        </aside>
    </div>
</div>