<?php

namespace App\Livewire\Warga\Laporan;

use App\Livewire\ComplaintForm;
use App\Models\Laporan;
use App\Services\ComplaintManager;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;

class Edit extends ComplaintForm
{
    #[Locked]
    public int $laporanId;

    public function mount(Laporan $laporan): void
    {
        $this->authorize('update', $laporan);
        $this->laporanId = $laporan->id;
        $this->judul = $laporan->judul;
        $this->detail = $laporan->detail;
        $this->tanggal = $laporan->tanggal->format('Y-m-d');
    }

    public function batalPilihFoto(): void
    {
        $this->reset('foto');
    }

    public function save(): void
    {
        $this->authorize('update', Laporan::findOrFail($this->laporanId));
        app(ComplaintManager::class)->update($this->laporanId, $this->validatedData());
        session()->flash('success', 'Perubahan pengaduan berhasil disimpan.');
        $this->redirectRoute('warga.laporan.show', ['laporan' => $this->laporanId]);
    }

    public function render(): View
    {
        $laporan = Laporan::findOrFail($this->laporanId);
        $this->authorize('update', $laporan);

        return view('livewire.warga.laporan.edit', compact('laporan'))->title('Edit Pengaduan');
    }
}