<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaporanSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laporan::create([
            // 'id'=> 1,
            'id_user'=> 1,
            'id_makanan'=> 1,
            'jumlah'=> 1, 
            'kalori'=> "100",
            'created_at'=> "2023-09-12",
            'updated_at'=> "2023-09-12",
        ]);
    }
}
