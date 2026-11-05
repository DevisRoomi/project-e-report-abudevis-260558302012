<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    public const STATUSES = ['pending' => 'Menunggu', 'proses' => 'Sedang Diproses', 'selesai' => 'Selesai'];

    protected $fillable = ['judul', 'detail', 'tanggal', 'foto'];

    protected $attributes = ['status' => 'pending'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function statusDescription(): string
    {
        return match ($this->status) {
            'proses' => 'Pengaduan sedang ditindaklanjuti.',
            'selesai' => 'Pengaduan telah selesai ditangani.',
            default => 'Pengaduan menunggu peninjauan admin.',
        };
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
            return asset('storage/' . ltrim($this->foto, '/'));
        }

        if (file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }

        if (file_exists(public_path('images/' . $this->foto))) {
            return asset('images/' . $this->foto);
        }

        return asset('storage/' . ltrim($this->foto, '/'));
    }
}