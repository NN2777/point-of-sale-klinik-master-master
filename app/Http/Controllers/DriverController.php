<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return view('dashboard.driver');
    }

    public function daftarOrder(){
        $user = auth()->user();
        $daftarOrder = Order::where(['id_requested', $user->id],['status','In_Progress']);

        return datatables()
            ->of($daftarOrder)
            ->addIndexColumn()
            ->addColumn('nama_requester', function ($daftarOrder) {
                return $daftarOrder->name;
            })
            ->addColumn('no_telp', function ($driverdata) {
                return $driverdata->phone_number;
            })
            ->addColumn('status', function ($driverdata) {
                $status = $driverdata->status ?? '';
                if($status=='available'){
                    return '<span class="label label-success">'. $status .'</spa>';
                }else {
                    return '<span class="label label-danger">'. $status .'</spa>';
                }
            })
            ->addColumn('aksi', function ($driverdata) {
                $status = auth()->user()->status;
                if($status == "Ordering"){
                    return '
                <div class="btn-group">
                    <a href="'. route('user.order', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat disabled">Ordering</a>
                </div>
                ';
                }else {
                    return '
                <div class="btn-group">
                    <a href="'. route('user.order', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat">Order</a>
                </div>
                ';
                }
            })
            // <button onclick="deleteData(`'. route('kategori.destroy', $kategori->id_kategori) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            ->rawColumns(['aksi','status'])
            ->make(true);
    }

    public function receiveOrder(){
        $order = Order::where;

        $requested = User::findOrFail(auth()->user()->id);
        $requested->status = 'Busy';
    }

    public function finsihOrder(){

        $customer->status = 'Available';
        
        $requested->status = 'Available';

        $order->status = 'Finished';
    }
}
