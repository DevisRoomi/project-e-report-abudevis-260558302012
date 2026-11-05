<?php

namespace App\Livewire\Warga;

use App\Livewire\WargaPage;
use App\Models\User;
use App\Support\StoredImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;

class Profile extends WargaPage
{
    use WithFileUploads;

    public string $name = '';

    public string $no_whatsapp = '';

    public string $alamat = '';

    public $foto;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->no_whatsapp = $user->no_whatsapp;
        $this->alamat = $user->alamat ?? '';
    }

    public function hapusFoto(): void
    {
        $user = auth()->user();
        if ($user->foto) {
            $oldPhoto = $user->foto;
            $user->update(['foto' => null]);
            StoredImage::delete($oldPhoto, 'profil');
            $user->refresh();
            session()->flash('success', 'Foto profil berhasil dihapus.');
        }
    }

    public function batalPilihFoto(): void
    {
        $this->reset('foto');
    }

    public function updateProfile(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:100',
            'no_whatsapp' => ['required', 'regex:/^\+?[0-9]{9,15}$/'],
            'alamat' => 'required|string|max:2000',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=10,min_height=10,max_width=6000,max_height=6000',
        ]);
        unset($data['foto']);
        if ($this->foto) {
            $data['foto'] = $this->foto->store('profil', 'public');
        }
        try {
            $oldPhoto = DB::transaction(function () use ($data): ?string {
                $user = User::query()->lockForUpdate()->findOrFail(auth()->id());
                $oldPhoto = $user->foto;
                $user->update($data);

                return $oldPhoto;
            });
        } catch (\Throwable $exception) {
            StoredImage::delete($data['foto'] ?? null, 'profil');
            throw $exception;
        }
        if (isset($data['foto'])) {
            StoredImage::delete($oldPhoto, 'profil');
        }
        auth()->user()->refresh();
        $this->reset('foto');
        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'string', 'max:255', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ]);
        $user = auth()->user();
        $user->password = $this->password;
        $user->setRememberToken(Str::random(60));
        $user->save();
        $this->reset('current_password', 'password', 'password_confirmation');
        session()->regenerate();
        session()->flash('success', 'Password berhasil diubah.');
    }

    public function render(): View
    {
        return view('livewire.warga.profile')->title('Profil Saya');
    }
}