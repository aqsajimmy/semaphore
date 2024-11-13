<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceController extends Controller
{
    public function downloadInvoice($id)
    {
        $data = Penjualan::with('penjualan_detail')->findOrFail($id);
        try {
            $pdf = Pdf::view('invoice', ['data' => $data])
                ->format('a4')
                ->download("invoice-{$id}.pdf");
            return $pdf;
        } catch (\Exception $e) {
            return toastr()->error('Failed to generate invoice :' . $e->getMessage());
        }
    }
}
