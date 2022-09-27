<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'administrator') {
            return view('admin.dashboard');
        } else if (auth()->user()->role == 'user'){
            return view('user.dashboard');
        } else {
            return response()->json('error', 400);
        }
    }
}
