<?php

namespace App\Http\Controllers;

use Validator;
use App\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DataController extends Controller
{
    //menampilkan view index
    public function index()
    {
        return view('index');
    }
    
    //menampilkan data dari database ke tabel
    public function table()
    {
        $berat = DB::table('data')->orderBy('id','desc')->paginate(10);
        $jumlah = DB::table('data')->count();
        return view('table',['berat' => $berat,'jumlah'=>$jumlah]);
    }
    
    //menyimpan data kelebihan muatan ke database
    public function store(Request $request){
        if($request->berat >= 3000){
            $data = new Data();
            $data->berat = $request->berat;
            
            $data->save();
            return response()->json(["message" => "Data kelebihan muatan telah ditambahkan"], 201);

        }
        
    }
    public function deleteAll(){
        Data::truncate(); //reset data
        return redirect(route('table'));
    }
}
