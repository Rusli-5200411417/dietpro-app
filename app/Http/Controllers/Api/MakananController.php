<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Makanan;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    public function index(){
        $makanan = Makanan::all();

        return $this->success($makanan);
    }
    
    public function success($data, $message = "success") {
        return  response()->json([
          'code'  =>  200,
          'message' =>  $message,
          'data'  => $data,
        ]);
      }
}
