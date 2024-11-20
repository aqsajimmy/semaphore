<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penjualan;
use Livewire\WithPagination;

class DetailPenjualan extends Component
{
    use WithPagination;
    public $id_penjualan;

    public function mount($id)
    {
        $this->id_penjualan = $id;
    }

    public function render()
    {
        $detail = Penjualan::with('penjualan_detail')->findOrFail($this->id_penjualan);
        return view('livewire.detail-penjualan', [
            'detail' => $detail
        ]);
    }
}
