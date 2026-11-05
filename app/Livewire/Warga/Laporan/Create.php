<?php

namespace App\Livewire\Warga\Laporan;

use App\Livewire\ComplaintForm;
use App\Models\Laporan;
use App\Support\StoredImage;
use Illuminate\Contracts\View\View;

class Create extends ComplaintForm
{
    public function mount(): void
    {
        $this->tanggal = today()->format('Y-m-d');
    }

    public function batalPilihFoto(): void
    {
        $this->reset('foto');
    }

    public function save(): void
    {
        $this->authorize('create', Laporan::class);
        $data = $this->validatedData();
        try {
            $laporan = new Laporan($data);
            $laporan->pelapor()->associate(auth()->user());
            $laporan->status = 'pending';
            $laporan->save();
        } catch (\Throwable $exception) {
            StoredImage::delete($data['foto'] ?? null, 'laporan');
            throw $exception;
        }
        session()->flash('success', 'Pengaduan berhasil dikirim. Kami akan segera meninjaunya.');
        $this->redirectRoute('warga.laporan.show', ['laporan' => $laporan->id]);
    }

    public function render(): View
    {
        return view('livewire.warga.laporan.create')->title('Buat Pengaduan');
    }
}