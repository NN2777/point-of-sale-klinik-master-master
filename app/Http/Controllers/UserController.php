<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {   
        return view('user.dashboard', compact(auth()->user()));
    }
    
    public function roledata(Request $request)
    {   
        $role = $request->role;

        if ($role == 'driver'){
            return view('user.driver.driver');
        }else if($role == 'teknisi'){
            return view('user.teknisi.teknisi');
        }else if($role== 'cs'){
            return view('user.cs.cs');
        }else {
            return response()->json('data tidka ada', 200);
        }
    }

    // public function driverpage(){
        
    //     $role =
    //     return view
    // }

    public function order(Request $request,$id)
    {   
        $requested = User::findOrFail($id);
        $order = new Order();
        $order->id_requester = auth()->id();
        $order->id_requested = $id;
        $order->deskripsi = $request->deskripsi;
        $order->request_role = $requested->role;
        $order->status = 'Waiting';
        $order->rating = 0;
        $order->save();

        $customer = User::findOrFail(auth()->id());
        $customer->status = 'Ordering';
        $customer->update();

        // dd($customer);
        return response()->json('Data berhasil disimpan', 200);
    }

    public function detail(){
        return view('user.driver.detail');
    }

    public function batalOrder($id)
    {  
        $order = Order::where([['id_requester', auth()->user()->id], ['id_requested', $id], ['status', 'Waiting']])->first();
        // dd($order);
        $order->status = 'Cancelled';
        $order->rating = 0;
        $order->update();

        $customer = User::findOrFail(auth()->user()->id);
        $customer->status = 'Available';
        $customer->update();

        return response()->json('Berhasil dibatalkan', 200);
    }


    public function driverdata()
    {   
        $driverdata = User::where('role', 'driver')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($driverdata)
            ->addIndexColumn()
            ->addColumn('nama', function ($driverdata) {
                return $driverdata->name;
            })
            ->addColumn('no_telp', function ($driverdata) {
                return $driverdata->phone_number;
            })
            ->addColumn('status', function ($driverdata) {
                $status = $driverdata->status ?? '';
                if($status=='Available'){
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
                            <div class="btn-group">
                                <a href="'. route('user.batal', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat">Cancel Order</a>
                             </div>
                            ';
                    // if($order->status == 'Waiting'){
                    //     return '
                    //         <div class="btn-group">
                    //             <a href="'. route('user.order', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat disabled">Ordering</a>
                    //         </div>
                    //         <div class="btn-group">
                    //             <a href="'. route('user.order', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat disabled">Ordering</a>
                    //         </div>
                    //         ';
                    // }else {
                }else {
                    return '
                <div class="btn-group">
                    <button onclick="addForm(`'. route('user.order', $driverdata->id) .'`)" class="btn btn-xs btn-info btn-flat">Order</button>
                </div>
                ';
                }
            })
            // <button onclick="addForm(`'. route('user.order', $driverdata->id) .'`)" class="btn btn-xs btn-info btn-flat">Order</button>
            // <a href="'. route('user.order', $driverdata->id) .'" class="btn btn-xs btn-primary btn-flat">Order</a>
            // <button onclick="deleteData(`'. route('kategori.destroy', $kategori->id_kategori) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            ->rawColumns(['aksi','status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->level = 2;
        $user->foto = '/img/user.jpg';
        $user->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != "") 
            $user->password = bcrypt($request->password);
        $user->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return response(null, 204);
    }

    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $user->name = $request->name;
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $user->foto = "/img/$nama";
        }

        $user->update();

        return response()->json($user, 200);
    }
}
