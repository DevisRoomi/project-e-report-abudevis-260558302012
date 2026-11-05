<?php

namespace App\Livewire;

use Livewire\WithFileUploads;

abstract class ComplaintForm extends WargaPage
{
    use WithFileUploads;

    public string $judul = '';

    public string $detail = '';

    public string $tanggal = '';

    public $foto;

    protected function validatedData(): array
    {
        $data = $this->validate([
            'judul' => 'required|string|max:255',
            'detail' => 'required|string|max:10000',
            'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=10,min_height=10,max_width=6000,max_height=6000',
        ]);
        unset($data['foto']);
        if ($this->foto) {
            $data['foto'] = $this->foto->store('laporan', 'public');
        }

        return $data;
    }
}