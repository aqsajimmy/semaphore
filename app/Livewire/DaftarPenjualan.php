<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penjualan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Invoice;

class DaftarPenjualan extends Component
{
    use WithPagination;
    public $search = '';
    public $pp = 5;
    protected $updatesQueryString = ['search'];
    public function destroy($id)
    {
        // toastr()->error('Kasir Tidak Dapat Menghapus Transaksi : ' . $id);
        try {
            $item = Penjualan::findOrFail($id);
            $isOnlyTransaction = Penjualan::where('kasir_id', $item->kasir_id)->count() == 1;

            // If it's the only transaction, create a new Penjualan with total_harga = 0
            if ($isOnlyTransaction) {
                Penjualan::create([
                    'total_harga' => 0,
                    'kasir_id' => $item->kasir_id,
                    'tanggal' => now(),
                ]);
            }
            $item->delete();
            toastr()->success('Transaksi berhasil di hapus.');
        } catch (\Exception $e) {
            toastr()->error('Transaksi gagal di hapus.');
        }
        return redirect(route('daftar_penjualan'));
    }
    public function updatingSearch()
    {
        $this->reset();  // Resets all component properties
        $this->resetPage();  // Resets pagination to the first page
    }

    public function sendWa($id)
    {
        try {
            $send = $this->sendInvoice($id); // Call the sendInvoice method

            if ($send) {
                toastr()->success('Invoice Terkirim ! ');
            } else {
                toastr()->error('Invoice Gagal Terkirim !');
            }
        } catch (\Exception $e) {
            toastr()->error('Invoice Gagal Terkirim !: <br>' . $e->getMessage());
        }
    }
    public function render()
    {
        $kasir_id = Auth::user()->id;

        $penjualan = Penjualan::query()
            ->where('kasir_id', $kasir_id)
            ->where('total_harga', '>', 0)
            ->orderBy('created_at', 'desc')
            ->when($this->search, fn($query) => $query->search($this->search))
            ->paginate($this->pp);


        return view('livewire.daftar-penjualan', compact('penjualan'));
    }

    public function sendInvoice($id)
    {
        $data = Penjualan::with('penjualan_detail')->findOrFail($id);
        $apiKey = "jimx";
        $type = "text";
        $number = $data->whatsapp ?? '6282287564411';
        $gtotal = number_format($data->total_harga, 0, '', ',');
        $tunai = number_format($data->tunai, 0, '', ',');
        $debit = number_format($data->debit, 0, '', ',');
        $kredit = number_format($data->kredit, 0, '', ',');

        $text = "*No. Transaksi\t: invoice-{$data->id}* \n";
        $text .= "=======================\n";
        $text .= "Pelanggan\t: {$data->nama_pelanggan} \n";
        $text .= "Detail\t\t:\n";
        foreach ($data->penjualan_detail as $detail) {
            $text .= "- {$detail->nama_barang}\t\t(Qty: {$detail->kuantitas} {$detail->satuan},\t\tHarga: Rp. " . number_format($detail->subtotal, 0, '', ',') . ")\n";
        }
        $text .= "=======================\n";
        $text .= "\nTotal Pembelian\t\t: Rp. {$gtotal}";
        $text .= "\nBayar Tunai\t\t\t: Rp. {$tunai}";
        $text .= "\nBayar Debit\t\t\t: Rp. {$debit}";
        $text .= "\nTotal Kredit\t\t\t: Rp. {$kredit}\n";
        $text .= "\nTerimakasih Telah Berbelanja di *Semaphore Bordir & Konveksi*.\n";
        $text .= "\n`layanan jimx.dev`";
        $file = "";
        $filename = "";

        $result = sendMessage($apiKey, $type, $number, $text, $file, $filename);

        return $result['status'] === 'true';
    }
    public function downloadInvoice($id)
    {
        // $data = Penjualan::with('penjualan_detail')->findOrFail($id);
        // try {
        //     return Invoice::make('invoice', ['data' => $data])
        //         ->format('a4')
        //         ->save("invoice-{$id}.pdf");

        //     // toastr()->success("Invoice-{$data->id} Generated");

        //     // return $pdf;
        // } catch (\Exception $e) {
        //     Log::error("Failed to generate invoice PDF: " . $e->getMessage());
        //     return toastr()->error('Failed to generate invoice :' . $e->getMessage());
        // }
    }

    public function download($id)
    {
        toastr()->success("Invoice-#{$id} Downloaded !");
    }
}
