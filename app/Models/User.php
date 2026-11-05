<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'no_whatsapp', 'alamat', 'foto'];

    protected $hidden = ['password', 'remember_token'];

    protected $attributes = ['role' => 'warga'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'pelapor_id');
    }

    public function dashboardRoute(): string
    {
        return $this->role === 'admin' ? 'admin.dashboard' : 'warga.dashboard';
    }

    public function fotoUrl(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto);
        }

        if (file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }

        if (file_exists(public_path('images/' . $this->foto))) {
            return asset('images/' . $this->foto);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto);
    }
}