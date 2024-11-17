<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

class InvoiceController extends Controller
{
    public function downloadInvoice($id)
    {
        $data = Penjualan::with('penjualan_detail')->findOrFail($id);

        $client = new Party([
            'name'          => 'Semaphore',
            'address'       => 'Jalan Sutan Syahrir No. 54 Tarok Dipo Bukittinggi.',
            'phone'         => '0813-6313-0824',
            'custom_fields' => [
                'Telepon 2'        => '0822-5972-6779',
            ],
        ]);

        $customer = new Party([
            'name'          => ucwords($data->nama_pelanggan),
            'custom_fields' => [
                'whatsapp' => $data->whatsapp ?? '-',
            ],
        ]);

        $items = [];
        foreach ($data->penjualan_detail as $itemDetail) {
            $items[] = InvoiceItem::make("{$itemDetail->nama_barang}")
                ->quantity($itemDetail->kuantitas)
                ->units($itemDetail->satuan)
                ->pricePerUnit($itemDetail->harga_satuan);
        }

        $status = '';
        if ($data->total_harga == ($data->tunai + $data->debit)) {
            $status = "Lunas";
        } else {
            "Belum Lunas";
        }


        $invoice = Invoice::make()
            // ->template('invoice')
            ->date($data->updated_at)
            ->dateFormat('D,d/m/Y')
            ->series('SEMAPHORE')
            ->sequence($data->id)
            ->seller($client)
            ->buyer($customer)
            ->status((($data->tunai + $data->debit) == $data->total_harga) ? "Lunas" : "Belum Lunas")
            ->currencySymbol('Rp. ')
            ->currencyCode('rupiah')
            ->currencyDecimals(0)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->setCustomData([
                'cashier_name' => $data->kasir->name,
                'tunai' => $data->tunai,
                'debit' => $data->debit,
                'kredit' => $data->kredit,
            ])
            ->logo(public_path('f545d39b-615d-4d98-a0fd-880fe0d43670.png'))
            ->save('public');

        return $invoice->stream();
    }
    public function invoicePdf($id)
    {
        $data = Penjualan::with('penjualan_detail')->findOrFail($id);

        $client = new Party([
            'name'          => 'Semaphore',
            'address'       => 'Jalan Sutan Syahrir No. 54 Tarok Dipo Bukittinggi.',
            'phone'         => '0813-6313-0824',
            'custom_fields' => [
                'Telepon 2'        => '0822-5972-6779',
            ],
        ]);

        $customer = new Party([
            'name'          => ucwords($data->nama_pelanggan),
            'custom_fields' => [
                'whatsapp' => $data->whatsapp ?? '-',
            ],
        ]);

        $items = [];
        foreach ($data->penjualan_detail as $itemDetail) {
            $items[] = InvoiceItem::make("{$itemDetail->nama_barang}")
                ->quantity($itemDetail->kuantitas)
                ->units($itemDetail->satuan)
                ->pricePerUnit($itemDetail->harga_satuan);
        }

        $invoice = Invoice::make()
            // ->template('invoice')
            ->date($data->updated_at)
            ->dateFormat('D,d/m/Y')
            ->series('SEMAPHORE')
            ->sequence($data->id)
            ->seller($client)
            ->buyer($customer)
            ->status((($data->tunai + $data->debit) == $data->total_harga) ? "Lunas" : "Belum Lunas")
            ->currencySymbol('Rp. ')
            ->currencyCode('rupiah')
            ->currencyDecimals(0)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->setCustomData([
                'cashier_name' => $data->kasir->name,
                'tunai' => $data->tunai,
                'debit' => $data->debit,
                'kredit' => $data->kredit,
            ])
            ->logo(public_path('f545d39b-615d-4d98-a0fd-880fe0d43670.png'))
            ->save('public');
        $link = $invoice->url();

        return $link;
    }
}
