<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#DCD5C8] pb-6">
        <div>
            <span class="font-mono text-[11px] font-bold tracking-[.2em] uppercase text-[#C59B27]">KONFIGURASI PENGGUNA</span>
            <h1 class="mt-1 font-serif text-3xl font-bold tracking-tight text-[#1C1819] sm:text-4xl">Profil Saya</h1>
            <p class="mt-1 text-xs leading-relaxed text-[#7A6B6E]">Kelola informasi pribadi, kontak verifikasi, dan keamanan kata sandi akun.</p>
        </div>
    </div>

    @if(session('success'))
        <div role="status" class="mb-6 flex items-start gap-3 rounded-lg border border-[#B8D7C2] bg-[#F3F7F4] p-4 text-xs font-mono text-[#1D5E38]">
            <i data-lucide="circle-check" aria-hidden="true" class="h-4 w-4 shrink-0 text-[#1D5E38]"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid items-start gap-6 lg:grid-cols-[280px_1fr]">
        <aside class="rounded-lg border border-[#DCD5C8] bg-white p-7 text-center shadow-xs">
            @if(auth()->user()->fotoUrl())
                <div class="relative mx-auto h-24 w-24">
                    <img src="{{ auth()->user()->fotoUrl() }}" alt="Foto profil Anda" class="h-24 w-24 rounded-full object-cover border border-[#DCD5C8]">
                    <button
                        type="button"
                        wire:click="hapusFoto"
                        wire:confirm="Hapus foto profil dan gunakan inisial nama?"
                        title="Hapus foto profil"
                        class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-[#85222E] text-white hover:bg-[#681B24] transition shadow-xs cursor-pointer border-2 border-white"
                    >
                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                    </button>
                </div>
            @else
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#FAF7F5] border border-[#ECE7DF] font-serif text-3xl font-bold text-[#4A151C]">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
            @endif
            <h2 class="mt-5 font-serif text-xl font-bold break-words text-[#1C1819]">{{ auth()->user()->name }}</h2>
            <p class="mt-1 font-mono text-xs break-words text-[#7A6B6E]">{{ auth()->user()->email }}</p>
            <span class="mt-4 inline-flex rounded-full bg-[#FAF7F5] px-4 py-1 font-mono text-[11px] font-bold uppercase tracking-wider text-[#4A151C] border border-[#ECE7DF]">Warga Terdaftar</span>
            <dl class="mt-6 space-y-4 border-t border-[#ECE7DF] pt-5 text-left font-mono text-xs">
                <div>
                    <dt class="text-[10px] uppercase tracking-wider text-[#7A6B6E]">WhatsApp</dt>
                    <dd class="mt-1 text-[#1C1819] font-medium">{{ auth()->user()->no_whatsapp ?: 'Belum diisi' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] uppercase tracking-wider text-[#7A6B6E]">Alamat Tinggal</dt>
                    <dd class="mt-1 leading-relaxed break-words text-[#554749]">{{ auth()->user()->alamat ?: 'Belum diisi' }}</dd>
                </div>
            </dl>
        </aside>

        <div class="space-y-6">
            <form wire:submit="updateProfile" class="rounded-lg border border-[#DCD5C8] bg-white p-6 sm:p-8 shadow-xs">
                <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">DATA IDENTITAS</span>
                <h2 class="mt-1 font-serif text-xl font-bold text-[#1C1819]">Informasi Pribadi</h2>
                <p class="mt-1 text-xs text-[#7A6B6E]">Pastikan informasi kontak Anda selalu valid untuk mempermudah verifikasi dinas terkait.</p>

                <div class="mt-7 space-y-5">
                    <div>
                        <label for="name" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            aria-describedby="name-error"
                            @error('name')
                            aria-invalid="true"
                            @enderror
                            wire:model="name"
                            autocomplete="name"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C] read-only:bg-[#FAF7F5]"
                        >
                        @error('name')
                            <p id="name-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            aria-describedby="email-help email-error"
                            @error('email')
                            aria-invalid="true"
                            @enderror
                            value="{{ auth()->user()->email }}"
                            readonly
                            class="w-full rounded-md border border-[#ECE7DF] bg-[#FAF7F5] px-4 py-2.5 font-mono text-xs text-[#7A6B6E] outline-none"
                        >
                        <p id="email-help" class="mt-2 font-mono text-[11px] leading-relaxed text-[#7A6B6E]">Alamat surel bersifat permanen sebagai identitas autentikasi.</p>
                        @error('email')
                            <p id="email-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="no_whatsapp" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Nomor WhatsApp</label>
                        <input
                            type="tel"
                            id="no_whatsapp"
                            name="no_whatsapp"
                            aria-describedby="no_whatsapp-error"
                            @error('no_whatsapp')
                            aria-invalid="true"
                            @enderror
                            wire:model="no_whatsapp"
                            autocomplete="tel"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C]"
                        >
                        @error('no_whatsapp')
                            <p id="no_whatsapp-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Alamat Tinggal</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            aria-describedby="alamat-error"
                            @error('alamat')
                            aria-invalid="true"
                            @enderror
                            wire:model="alamat"
                            autocomplete="street-address"
                            rows="3"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C] min-h-32"
                        ></textarea>
                        @error('alamat')
                            <p id="alamat-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="foto" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Foto Profil</label>
                        <div
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false; progress = 0"
                            x-on:livewire-upload-error="uploading = false; progress = 0"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
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
                                class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#554749] outline-none file:mr-3 file:rounded-md file:border-0 file:bg-[#FAF7F5] file:px-3 file:py-1.5 file:font-mono file:text-xs file:font-bold file:text-[#4A151C] file:border file:border-[#ECE7DF]"
                            >
                            <div x-show="uploading" class="mt-2 w-full bg-[#ECE7DF] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#4A151C] h-1.5 rounded-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                            </div>
                            <p x-show="uploading" class="mt-1 font-mono text-[11px] text-[#4A151C]" x-text="'Mengunggah foto: ' + progress + '%'"></p>
                        </div>
                        <p id="foto-help" class="mt-2 font-mono text-[11px] leading-relaxed text-[#7A6B6E]">Format berkas JPG, PNG, atau WebP. Ukuran maksimal 2 MB.</p>
                        @error('foto')
                            <p id="foto-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror

                        @if ($foto && method_exists($foto, 'temporaryUrl') && method_exists($foto, 'isPreviewable') && $foto->isPreviewable())
                            <div class="mt-4 flex items-center justify-between rounded-lg border border-[#ECD9C5] bg-[#FAF5EE] p-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $foto->temporaryUrl() }}" alt="Pratinjau foto profil baru" class="h-16 w-16 rounded-full object-cover border border-[#DCD5C8]">
                                    <div>
                                        <p class="font-mono text-xs font-bold text-[#7C3D0E]">Pratinjau Foto Baru</p>
                                        <p class="font-mono text-[11px] text-[#A16207]">Foto siap disimpan saat Anda menekan tombol di bawah.</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    wire:click="batalPilihFoto"
                                    class="inline-flex items-center gap-1 rounded border border-[#DCD5C8] bg-white px-2.5 py-1 font-mono text-[11px] font-bold text-[#85222E] hover:bg-[#FAF7F5] transition"
                                >
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                    Batal
                                </button>
                            </div>
                        @endif
                    </div>
                    <p wire:loading wire:target="foto" class="font-mono text-xs text-[#4A151C]">Mengunggah foto…</p>
                </div>
                <div class="mt-7 flex justify-end">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updateProfile,foto"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-6 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15] cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="updateProfile">Simpan Profil</span>
                        <span wire:loading wire:target="updateProfile">Menyimpan…</span>
                    </button>
                </div>
            </form>

            <form wire:submit="updatePassword" class="rounded-lg border border-[#DCD5C8] bg-white p-6 sm:p-8 shadow-xs">
                <div class="flex items-center gap-3 border-b border-[#ECE7DF] pb-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-[#FAF7F5] border border-[#ECE7DF] text-[#4A151C]">
                        <i data-lucide="lock-keyhole" aria-hidden="true" class="h-4 w-4 shrink-0"></i>
                    </span>
                    <div>
                        <span class="font-mono text-[10px] font-bold tracking-wider uppercase text-[#C59B27]">KEAMANAN KREDENSIAL</span>
                        <h2 class="mt-0.5 font-serif text-xl font-bold text-[#1C1819]">Ubah Password</h2>
                        <p class="mt-0.5 text-xs text-[#7A6B6E]">Gunakan kombinasi sandi yang kuat untuk menjaga privasi berkas Anda.</p>
                    </div>
                </div>
                <div class="mt-7 space-y-5">
                    <div>
                        <label for="current_password" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Password Saat Ini</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            aria-describedby="current_password-error"
                            @error('current_password')
                            aria-invalid="true"
                            @enderror
                            wire:model="current_password"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C]"
                        >
                        @error('current_password')
                            <p id="current_password-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Password Baru</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            aria-describedby="password-help password-error"
                            @error('password')
                            aria-invalid="true"
                            @enderror
                            wire:model="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C]"
                        >
                        <p id="password-help" class="mt-2 font-mono text-[11px] leading-relaxed text-[#7A6B6E]">Minimal 8 karakter, wajib mengandung kombinasi huruf dan angka.</p>
                        @error('password')
                            <p id="password-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-[#1C1819]">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            aria-describedby="password_confirmation-error"
                            @error('password_confirmation')
                            aria-invalid="true"
                            @enderror
                            wire:model="password_confirmation"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-md border border-[#DCD5C8] bg-white px-4 py-2.5 font-mono text-xs text-[#1C1819] outline-none transition focus:border-[#4A151C] focus:ring-1 focus:ring-[#4A151C]"
                        >
                        @error('password_confirmation')
                            <p id="password_confirmation-error" role="alert" class="mt-2 font-mono text-xs text-[#85222E]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-7 flex justify-end">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-[#4A151C] px-6 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#3C0F15] cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="updatePassword">Ubah Password</span>
                        <span wire:loading wire:target="updatePassword">Menyimpan…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>