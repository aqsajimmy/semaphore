<?php

namespace App\Livewire;

use App\Http\Controllers\WhatsappSender;
use Livewire\Component;
use App\Models\Penjualan;
use Livewire\WithPagination;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Jobs\SendWaJob;

class FormPenjualan extends Component
{
    use WithPagination;
    public $no_transaksi, $tunai, $debit, $kredit, $kembalian,
        $total_harga, $nama_pelanggan, $kasir_nama, $tanggal,
        $nama_barang, $kuantitas, $harga_satuan, $satuan, $subtotal, $whatsapp;
    public function mount()
    {

        $this->resetFields();
        // inisiasi awal
        $kasir = Auth::user();
        $init = [
            'tanggal' => now(),
            'total_harga' => 0,
            'nama_pelanggan' => '',
            'kasir_id' => $kasir->id,
        ];

        $kasir_id = $kasir->id;
        $this->kasir_nama = ucwords($kasir->name);
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
        if (is_numeric($this->kuantitas) && $this->kuantitas >= 0) {
            $this->calculateSubtotals(); // Call the method to update subtotal
        } else {
            $this->subtotal = 0; // Reset subtotal if kuantitas is invalid
        }
    }

    public function updatedHargaSatuan()
    {
        if (is_numeric($this->harga_satuan) && $this->harga_satuan >= 0) {
            $this->calculateSubtotals(); // Call the method to update subtotal
        } else {
            $this->subtotal = 0; // Reset subtotal if harga_satuan is invalid
        }
    }

    public function calculateSubtotals()
    {
        if (is_numeric($this->kuantitas) && is_numeric($this->harga_satuan)) {
            $subtot = number_format($this->kuantitas * $this->harga_satuan, 0, '', ',');
            $this->subtotal = $subtot;
        } else {
            $this->subtotal = 0; // Reset subtotal if either value is invalid
        }
        $this->calculateKembaliannKredit();
    }

    public function calculateTotalHarga()
    {
        $totalh = number_format(PenjualanDetail::where('id_penjualan', $this->no_transaksi)->sum('subtotal'), 0, '', ',');
        $this->total_harga = $totalh;
        $this->calculateKembaliannKredit();
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
        $nb = $this->nama_barang ?? ''; // Default to empty string if not set
        $qty = $this->kuantitas ?? 0; // Default to 0 if not set
        $stn = $this->satuan ?? ''; // Default to empty string if not set
        $hrgstn = (int) str_replace(',', '', $this->harga_satuan) ?? 0; // Default to 0 if not set

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
        $whatsapp = $this->whatsapp;
        if (preg_match('/^08/', $whatsapp)) {
            $whatsapp = preg_replace('/^08/', '628', $whatsapp);
        } elseif (preg_match('/^62/', $whatsapp)) {
            $whatsapp;
        }
        $total_i = (int) str_replace(',', '', $this->total_harga);
        $tunai_i = (int) str_replace(',', '', $this->tunai);
        $kembalian_i = (int) str_replace(',', '', $this->kembalian);
        $debit_i = (int) str_replace(',', '', $this->debit);
        $tunai_b = $debit_i;
        if ($debit_i <= $total_i) {
            $tunai_b = ($tunai_i - $kembalian_i);
        }
        $penjualan->update([
            'tanggal' => $this->tanggal,
            'total_harga' => $total_i,
            'tunai' => $tunai_b,
            'debit' => $debit_i,
            'kredit' => (int) str_replace(',', '', $this->kredit),
            'nama_pelanggan' => $this->nama_pelanggan,
            'whatsapp' => $whatsapp,
            'kasir_id' => $penjualan->kasir_id
        ]);

        if ($penjualan) {
            toastr()->success('Transaksi Berhasil Tersimpan');
        } else {
            toastr()->error('Transaksi Gagal Tersimpan');
        }
        try {
            SendWaJob::dispatch($penjualan->id); // Dispatch the job asynchronously
        } catch (\Exception $e) {
            toastr()->error('Failed to queue WhatsApp message: ' . $e->getMessage());
        }
        return redirect(route('penjualan'));
    }

    public function calculateKembaliannKredit()
    {
        $this->kredit = null;
        $this->kembalian = null;
        $tunai = (int) ($this->tunai ?? 0);
        $debit = (int) ($this->debit ?? 0);
        $total = (int) str_replace(',', '', $this->total_harga ?? '0');

        if ($debit > $total) {
            toastr()->error('Nilai Debit Tidak Boleh Lebih dari Total Belanja: Rp.' . number_format($total, 0, '', ','));
            return redirect(route('penjualan'));
        }

        $total_bayar = $tunai + $debit;

        $kembalian = $total_bayar - $total;
        $kredit = $total - $total_bayar;

        if ($total_bayar > $total) {
            $this->kembalian = number_format($kembalian, 0, '', ',');
        } elseif ($total_bayar < $total) {
            $this->kredit = number_format($kredit, 0, '', ',');
        } else {
            $this->kredit = null;
            $this->kembalian = null;
        }
    }

    public function resetFields()
    {
        $this->nama_barang = null;
        $this->nama_pelanggan = 'umum';
        $this->kuantitas = null;
        $this->satuan = 'pcs';
        $this->harga_satuan = null;
        $this->total_harga = 0;
        $this->subtotal = 0;
        $this->tunai = null;
        $this->debit = null;
        $this->kredit = null;
        $this->kembalian = null;
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

    public function sendWa($id)
    {
        try {
            // Instantiate the DaftarPenjualan class
            $daftarPenjualan = new DaftarPenjualan();

            // Call the sendInvoice method on the instance
            $send = $daftarPenjualan->sendInvoice($id);

            // Check if the invoice was sent successfully
            if ($send) {
                toastr()->success('Invoice Terkirim!');
            } else {
                toastr()->error('Invoice Gagal Terkirim!');
            }
        } catch (\Exception $e) {
            // Handle exceptions and display error message
            toastr()->error('Invoice Gagal Terkirim!<br>' . $e->getMessage());
        }
    }
}
