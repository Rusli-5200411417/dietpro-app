<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = ['id_user', 'id_makanan','kalori','nama_bahan','jumlah','jumlah_kalori']; // Make sure 'kalori' is not in the $fillable array

    // Define the 'kalori' attribute with a default value
    protected static function booted()
    {
        static::creating(function ($laporan) {
            if (!empty($laporan->id_makanan)) {
                $makanan = Makanan::find($laporan->id_makanan);
                if ($makanan) {
                    $laporan->kalori = $makanan->Energi_kkal;
                    $laporan->nama_bahan = $makanan->Nama_Bahan;
                }
            }
        });
        
        static::saving(function ($laporan) {
            $laporan->jumlah_kalori = $laporan->kalori * $laporan->jumlah;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'id_makanan');
    }
}
