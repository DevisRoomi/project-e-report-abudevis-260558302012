<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">PERBAIKAN DOKUMEN</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Edit Pengaduan</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Perubahan hanya dapat diajukan selama berkas laporan berstatus antrean menunggu.</p>
        </div>
    </div>

    <form wire:submit="save" class="max-w-3xl rounded-lg border border-[#DCD5C8] bg-white p-6 sm:p-8 shadow-xs">
        <div class="mb-6 border-b border-[#ECE7DF] pb-4">
            <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">BERKAS #{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</span>
            <h2 class="mt-1 font-serif text-xl font-bold text-[#1C1819]">Koreksi Detail Laporan</h2>
        </div>

        {{-- Formulir pengaduan: setiap input terhubung ke properti Livewire --}}
        <div class="space-y-6">
            <div>
                <label for="judul" class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Judul pengaduan</label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    aria-describedby="judul-help judul-error"
                    @error('judul')
                    aria-invalid="true"
                    @enderror
                    wire:model="judul"
                    placeholder="Contoh: Lampu penerangan jalan padam di Jl. Merpati RW 04"
                    maxlength="255"
                    required
                    class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-4 py-3 text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C]"
                >
                <p id="judul-help" class="mt-1.5 text-[11px] leading-relaxed text-[#7A6B6E]">Gunakan judul singkat yang menggambarkan masalah secara akurat.</p>
                @error('judul')
                    <p id="judul-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="detail" class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Deskripsi pengaduan</label>
                <textarea
                    id="detail"
                    name="detail"
                    aria-describedby="detail-help detail-error"
                    @error('detail')
                    aria-invalid="true"
                    @enderror
                    wire:model="detail"
                    rows="6"
                    placeholder="Ceritakan masalah secara jelas, termasuk lokasi dan dampaknya bagi warga…"
                    maxlength="10000"
                    required
                    class="w-full rounded-md border border-[#DCD5C8] bg-[#FAF7F5] px-4 py-3 text-xs leading-relaxed text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:bg-white focus:ring-1 focus:ring-[#4A151C] min-h-32"
                ></textarea>
                <p id="detail-help" class="mt-1.5 text-[11px] leading-relaxed text-[#7A6B6E]">Jelaskan lokasi kejadian dalam deskripsi. Maksimal 10.000 karakter.</p>
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
                <div>
                    <label for="foto" class="mb-2 block font-mono text-xs font-semibold uppercase tracking-wider text-[#1C1819]">Foto bukti baru (opsional)</label>
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
                    <p id="foto-help" class="mt-2 text-[11px] leading-relaxed text-[#7A6B6E]">JPG, PNG, atau WebP. Maksimal 2 MB. Kosongkan jika tidak ingin mengubah foto bukti.</p>
                    @error('foto')
                        <p id="foto-error" role="alert" class="mt-1.5 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                    @enderror

                    @if ($foto && method_exists($foto, 'temporaryUrl') && method_exists($foto, 'isPreviewable') && $foto->isPreviewable())
                        <div class="mt-4 rounded-md border border-[#DCD5C8] bg-white p-3">
                            <p class="mb-2 font-mono text-[11px] font-bold uppercase tracking-wider text-[#4A151C]">Pratinjau Foto Bukti Baru:</p>
                            <img src="{{ $foto->temporaryUrl() }}" alt="Pratinjau foto bukti baru" class="max-h-56 rounded border border-[#ECE7DF] object-contain">
                        </div>
                    @endif
                </div>
                <p wire:loading wire:target="foto" role="status" class="mt-3 font-mono text-xs text-[#4A151C]">Mengunggah foto…</p>
                @if($laporan->fotoUrl())
                    <div class="mt-4 border-t border-[#ECE7DF] pt-4">
                        <p class="mb-2 font-mono text-[11px] text-[#7A6B6E]">Foto bukti saat ini:</p>
                        <img src="{{ $laporan->fotoUrl() }}" alt="Foto bukti saat ini" class="max-h-44 rounded-md border border-[#DCD5C8] object-contain">
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-[#ECE7DF] pt-6">
            <a
                href="{{ route('warga.laporan.show', $laporan) }}"
                class="inline-flex items-center justify-center rounded-md border border-[#DCD5C8] bg-white px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-[#554749] hover:bg-[#FAF7F5] transition"
            >Batal</a>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save,foto"
                class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-6 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15]"
            >
                <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                <span wire:loading wire:target="save">Menyimpan…</span>
            </button>
        </div>
    </form>
</div>