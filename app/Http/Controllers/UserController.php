<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function tampilUser(){

        $users = User::where('role', 'user')->get();
        // dd($users);
        return view('user', compact('users'));
    }

    public function newUser(){
        $user = User::where('role', 'user')->where('created_at',  '>=', now()->startOfDay())->get(); 

            return view ('new-user',compact('user'));
    }

    public function dailyUsers()
    {
        // Query untuk mengambil jumlah pengguna setiap hari
        $dailyUsers = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        $data = [];

        foreach ($dailyUsers as $dailyUser) {
            $data[$dailyUser->date] = $dailyUser->count;
        }

        return response()->json($data);
    }
}
