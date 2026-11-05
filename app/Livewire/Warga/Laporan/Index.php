<?php

namespace App\Livewire\Warga\Laporan;

use App\Livewire\WargaPage;
use App\Models\Laporan;
use App\Services\ComplaintManager;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Index extends WargaPage
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        app(ComplaintManager::class)->delete($id);
        session()->flash('success', 'Pengaduan berhasil dihapus.');
    }

    public function render(): View
    {
        $this->validate(['search' => 'max:255', 'status' => ['nullable', Rule::in(array_keys(Laporan::STATUSES))]]);
        $laporans = auth()->user()->laporans()
            ->when($this->search !== '', fn ($q) => $q->where('judul', 'like', '%'.$this->search.'%'))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest()->orderByDesc('id')->paginate(10);

        return view('livewire.warga.laporan.index', compact('laporans'))->title('Pengaduan Saya');
    }
}