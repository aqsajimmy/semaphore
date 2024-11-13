<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PenjualanController extends Controller
{

    public function index(): View
    {

        $data = [];
        return view('penjualan.index', [
            'data' => $data,
        ]);
    }
    public function daftar_penjualan(): View
    {

        $data = [];
        return view('penjualan.daftar-penjualan', [
            'data' => $data,
        ]);
    }
}
