<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penjualan;
use Livewire\WithPagination;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPdf\Facades\Pdf;

class FormPenjualan extends Component
{
    use WithPagination;
    public $no_transaksi, $nama_pelanggan, $total_harga, $tanggal, $nama_barang, $kuantitas, $harga_satuan, $satuan, $subtotal;
    public function mount()
    {

        $this->resetFields();
        // inisiasi awal
        $kasir = Auth::user();
        $init = [
            'tanggal' => now(),
            'total_harga' => 0,
            'nama_pelanggan' => '',
            'kasir_id' => $kasir->id
        ];

        $kasir_id = $kasir->id;
        $last_transaksi = Penjualan::where('kasir_id', $kasir_id)->latest()->first();
        $last_total_harga = $last_transaksi->total_harga ?? 0;

        if ($last_total_harga == 0 && $last_transaksi->kasir_id == $kasir_id) {
            $this->no_transaksi = $last_transaksi->id;
        } else {
            $new = Penjualan::create($init);
            $this->no_transaksi = $new->id;
        }
        $this->calculateTotalHarga();
        $this->tanggal = now()->format('Y-m-d');

        // inisiasi awal
    }

    public function updatedKuantitas()
    {
        // Ensure kuantitas is a valid number before calculating subtotal
        if (is_numeric($this->kuantitas) && $this->kuantitas >= 0) {
            $this->calculateSubtotals(); // Call the method to update subtotal
        } else {
            $this->subtotal = 0; // Reset subtotal if kuantitas is invalid
        }
    }

    public function updatedHargaSatuan()
    {
        // Ensure harga_satuan is a valid number before calculating subtotal
        if (is_numeric($this->harga_satuan) && $this->harga_satuan >= 0) {
            $this->calculateSubtotals(); // Call the method to update subtotal
        } else {
            $this->subtotal = 0; // Reset subtotal if harga_satuan is invalid
        }
    }

    public function calculateSubtotals()
    {
        // Ensure both kuantitas and harga_satuan are valid numbers
        if (is_numeric($this->kuantitas) && is_numeric($this->harga_satuan)) {
            $this->subtotal = $this->kuantitas * $this->harga_satuan;
        } else {
            $this->subtotal = 0; // Reset subtotal if either value is invalid
        }
    }

    public function calculateTotalHarga()
    {
        $this->total_harga = number_format(PenjualanDetail::where('id_penjualan', $this->no_transaksi)->sum('subtotal'), 0, '.', '.');
    }

    public function add()
    {
        $validatedDate = $this->validate(
            [
                'nama_barang' => 'required',
                'kuantitas' => 'required',
                'satuan' => 'required',
                'harga_satuan' => 'required',
                'subtotal' => 'required'
            ],

            [

                'nama_barang.required' => 'nama barang field is required',
                'kuantitas.required' => 'kuantitas field is required',
                'satuan.required' => 'satuan field is required',
                'harga_satuan.required' => 'harga satuan field is required',
                'subtotal.required' => 'subtotal field is required'
            ]

        );

        $ntr = $this->no_transaksi;
        $nb = $this->nama_barang = $this->nama_barang ?? ''; // Default to empty string if not set
        $qty = $this->kuantitas = $this->kuantitas ?? 0; // Default to 0 if not set
        $stn = $this->satuan = $this->satuan ?? ''; // Default to empty string if not set
        $hrgstn = $this->harga_satuan = $this->harga_satuan ?? 0; // Default to 0 if not set

        $items = [
            'id_penjualan' => $ntr,
            'nama_barang' => $nb,
            'kuantitas' => $qty,
            'satuan' => $stn,
            'harga_satuan' => $hrgstn,
            'subtotal' => $qty * $hrgstn,
        ];
        $add = PenjualanDetail::create($items);
        return redirect(route('penjualan'));
    }

    public function updatePenjualan($no_transaksi)
    {
        if ($this->total_harga == 0) {
            toastr()->error('Transaksi Tidak Boleh Kosong');
        }
        $validatedDate = $this->validate(
            [
                'tanggal' => 'required',
                'no_transaksi' => 'required|exists:penjualan_detail,id_penjualan',
                'nama_pelanggan' => 'string',
                'total_harga' => 'required'
            ],

            [

                'tanggal.required' => 'tanggal barang field is required',
                'no_transaksi.required' => 'no transaksi field is required',
                'nama_pelanggan.string' => 'nama pelanggan salah',
                'total_harga.required' => 'pembelian kosong'
            ]

        );

        $this->calculateTotalHarga();
        $penjualan = Penjualan::findOrFail($no_transaksi);
        $penjualan->update([
            'tanggal' => $this->tanggal,
            'total_harga' => (int) str_replace('.', '', $this->total_harga),
            'nama_pelanggan' => $this->nama_pelanggan,
            'kasir_id' => Auth::user()->id
        ]);

        if ($penjualan) {
            toastr()->success('Transaksi Berhasil Tersimpan');
        } else {
            toastr()->error('Transaksi Gagal Tersimpan');
        }
        return redirect(route('penjualan'));
    }

    public function resetFields()
    {
        $this->nama_barang = '';
        $this->nama_pelanggan = 'umum';
        $this->kuantitas = '';
        $this->satuan = 'pcs';
        $this->harga_satuan = '';
        $this->subtotal = 0;
    }

    public function destroy($id)
    {
        try {
            $item = PenjualanDetail::findOrFail($id);
            $item->delete();
            toastr()->success('Items berhasil di hapus.');
        } catch (\Exception $e) {
            toastr()->error('Items gagal di hapus.');
        }
        return redirect(route('penjualan'));
    }

    public function render()
    {
        $items_details = PenjualanDetail::where('id_penjualan', $this->no_transaksi)
            ->orderBy('id', 'desc')->paginate(5);

        return view('livewire.form-penjualan', [
            'items_details' => $items_details,
        ]);
    }
    public function sendWa()
    {
        try {

            $send = $this->sendInvoice(); // Call the sendInvoice method
            if ($send) {
                toastr()->success('Invoice Terkirim ! ');
            } else {
                toastr()->error('Invoice Gagal Terkirim !');
            }
        } catch (\Exception $e) {
            toastr()->error('Invoice Gagal Terkirim !: <br>' . $e->getMessage());
        }
    }

    public function sendInvoice()
    {

        $number = '6282287564411';
        $text = 'Hello, this is a test message ' . time();
        $file = 'https://placehold.co/600x400'; // Optional if sending image, video, or document
        $apiKey = '0x';
        $type = 'document'; // Can be 'text', 'image', 'video', or 'document'
        $filename = 'example.jpg'; // Optional custom filename

        // Call the sendMessage function
        $result = sendMessage($number, $text, $apiKey, $file, $type, $filename);

        // Check the result
        return $result['status'] === 'true';
    }
}
