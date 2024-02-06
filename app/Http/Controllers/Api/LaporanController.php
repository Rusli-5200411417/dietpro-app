<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
  
  public function index($id){ 
    $today = date('Y-m-d');
    $data = Laporan::with(['user', 'makanan'])
    ->where('id_user', $id )
    ->whereDate('created_at', $today)
    ->get();

      // Menghitung total kalori dari semua rekaman
      $total_kalori = $data->sum(function ($data) {
        // Pastikan bahwa kolom 'kalori' tidak kosong
        return !empty($data->jumlah_kalori) ? (int)$data->jumlah_kalori : 0;
    });

    return  response()->json([
      'code'  =>  200,
      'message' =>  "data Laporan berhasil dimuat untuk id user $id",
      'data' => $data
    //   'totalKalori' => $total_kalori,    
    ]);
  }

    public function dataSatuMinggu($id) {
        $today = date('Y-m-d'); // Tanggal hari ini
        $currentDayOfWeek = date('N', strtotime($today)); // Hari dalam format ISO (1 = Senin, 7 = Minggu)

        // Hitung tanggal awal (Senin) dan tanggal akhir (Minggu) dari minggu ini
        $startDate = date('Y-m-d', strtotime("-" . ($currentDayOfWeek - 1) . " days", strtotime($today)));
        $endDate = date('Y-m-d', strtotime("+" . (7 - $currentDayOfWeek) . " days", strtotime($today)));

        $data = Laporan::with(['user', 'makanan'])
            ->where('id_user', $id)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Menghitung total kalori dari semua rekaman
        $total_kalori = $data->sum(function ($data) {
            // Pastikan bahwa kolom 'kalori' tidak kosong
            return !empty($data->jumlah_kalori) ? (int)$data->jumlah_kalori : 0;
        });

        return response()->json([
            'code' => 200,
            'message' => "Data Laporan berhasil dimuat untuk id user $id dari hari Senin tanggal $startDate hingga hari Minggu tanggal $endDate",
            'data' => $data,
        ]);
    }


    public function dataSatuBulan($id) {
        $today = date('Y-m-d'); // Tanggal hari ini
        $currentMonth = date('m', strtotime($today)); // Bulan saat ini
    
        // Hitung tanggal awal (1st) dan tanggal akhir (terakhir) dari bulan ini
        $startDate = date('Y-m-01', strtotime($today));
        $endDate = date('Y-m-t', strtotime($today));
    
        $data = Laporan::with(['user', 'makanan'])
            ->where('id_user', $id)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
    
        // Menghitung total kalori dari semua rekaman
        $total_kalori = $data->sum(function ($data) {
            // Pastikan bahwa kolom 'kalori' tidak kosong
            return !empty($data->jumlah_kalori) ? (int)$data->jumlah_kalori : 0;
        });
    
        return response()->json([
            'code' => 200,
            'message' => "Data Laporan berhasil dimuat untuk id user $id selama satu bulan dari tanggal $startDate hingga $endDate",
            'data' => $data,
        ]);
    }
       

  public function store(Request $request, $id)
  {
      // Validate the request data
      $validator = Validator::make($request->all(), [
          'id_makanan' => 'required|array', // Ensure id_makanan is an array
          'id_makanan.*' => 'exists:makanan,id', // Validate each id_makanan element exists in the makanans table
          'jumlah' => 'required|array'
        ]);
  
      if ($validator->fails()) {
          return response()->json([
              'code' => 400,
              'message' => 'Validation error',
              'errors' => $validator->errors(),
          ], 400);
      }
  
      $today = date('Y-m-d'); // Mendapatkan tanggal hari ini dalam format Y-m-d
  
      $laporan = [];
  
      foreach ($request->input('id_makanan') as $index => $makananId) {
        $jumlah = $request->input('jumlah')[$index]; // Ambil nilai jumlah yang sesuai dengan indeks saat ini

        // Cek apakah laporan dengan id_makanan yang sama sudah ada pada hari yang sama
        $existingLaporan = Laporan::where('id_user', $id)
            ->where('id_makanan', $makananId)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingLaporan) {
            // Jika sudah ada, tambahkan jumlah baru ke jumlah lama
            $existingLaporan->jumlah += $jumlah;

            if ($existingLaporan->jumlah == $jumlah) {
                // Jika jumlah sekarang sama dengan jumlah baru, set jumlah_kalori sama dengan kalori
                $existingLaporan->jumlah_kalori = $existingLaporan->kalori * $existingLaporan->jumlah;
            } else {
                // Jika jumlah sekarang tidak sama dengan jumlah baru, hitung jumlah_kalori baru
                $existingLaporan->jumlah_kalori += $existingLaporan->kalori * $jumlah;
            }

            $existingLaporan->save();
            $laporan[] = $existingLaporan; // Tambahkan laporan ke array respons
        } else {
            // Jika belum ada, buat laporan baru
            $newLaporan = new Laporan();
            $newLaporan->id_user = $id;
            $newLaporan->id_makanan = $makananId;
            $newLaporan->jumlah = $jumlah; // Gunakan nilai jumlah yang diambil dari permintaan

            // Dapatkan kalori dari makanan yang dipilih
            $makanan = Makanan::find($makananId);
            if ($makanan) {
                $newLaporan->kalori = $makanan->Energi_kkal;
                $newLaporan->jumlah_kalori = $newLaporan->kalori * $jumlah;
            }

            // Set other fields as needed
            $newLaporan->save();
            $laporan[] = $newLaporan; // Tambahkan laporan baru ke array respons
        }
      }
      return response()->json([
          'code' => 200,
          'message' => 'Laporan created or updated successfully',
          'data' => $laporan, // Sisipkan laporan dalam respons
      ], 200);
  }

  public function detail($id){
    // Menggunakan metode find untuk mencari laporan berdasarkan ID
    $data = Laporan::find($id);

    // Periksa apakah laporan ditemukan
    if (!$data) {
        return response()->json([
            'code' => 404,
            'message' => "Data dengan ID $id tidak ditemukan",
            'data' => null
        ], 404);
    }

    return  response()->json([
        'code'  =>  200,
        'message' =>  "Data dengan ID $id berhasil dimuat",
        'data' => $data   
    ]);
    } 

    public function delete($id)
    {
        // Cari laporan berdasarkan ID
        $laporan = Laporan::find($id);

        // Periksa apakah laporan ditemukan
        if (!$laporan) {
            return response()->json([
                'code' => 404,
                'message' => "Data dengan ID $id tidak ditemukan",
            ], 404);
        }

        // Hapus laporan
        $laporan->delete();

        return response()->json([
            'code' => 200,
            'message' => "Data dengan ID $id berhasil dihapus",
        ]);
    }
}


