<?php

namespace App\Livewire\Admin\Laporan;

use App\Livewire\AdminPage;
use App\Models\Laporan;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Index extends AdminPage
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $sort = 'newest';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'sort']);
        $this->resetPage();
    }

    public function exportExcel()
    {
        $direction = $this->sort === 'oldest' ? 'asc' : 'desc';
        $laporans = Laporan::with('pelapor')
            ->when($this->search !== '', fn ($q) => $q->where(function ($q): void {
                $q->where('judul', 'like', '%'.$this->search.'%')
                    ->orWhereHas('pelapor', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            }))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->orderBy('created_at', $direction)->orderBy('id', $direction)->get();

        $filename = 'rekap-pengaduan-'.date('Y-m-d-His').'.xls';

        return response()->streamDownload(function () use ($laporans) {
            echo view('exports.laporan-excel', ['laporans' => $laporans])->render();
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function render(): View
    {
        $this->validate([
            'search' => 'max:255',
            'status' => ['nullable', Rule::in(array_keys(Laporan::STATUSES))],
            'sort' => ['required', Rule::in(['newest', 'oldest'])],
        ]);
        $direction = $this->sort === 'oldest' ? 'asc' : 'desc';
        $laporans = Laporan::with('pelapor')
            ->when($this->search !== '', fn ($q) => $q->where(function ($q): void {
                $q->where('judul', 'like', '%'.$this->search.'%')
                    ->orWhereHas('pelapor', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            }))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->orderBy('created_at', $direction)->orderBy('id', $direction)->paginate(10);

        return view('livewire.admin.laporan.index', compact('laporans'))->title('Semua Pengaduan');
    }
}