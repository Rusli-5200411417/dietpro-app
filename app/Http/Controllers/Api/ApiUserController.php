<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
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
