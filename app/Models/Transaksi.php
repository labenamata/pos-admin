<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    
    protected $table = 'transaksi';
    
    protected $fillable = [
        'tanggal',
        'nama_pelanggan',
        'total',
        'diskon',
        'total_bayar',
        'metode_pembayaran',
        'status'
    ];
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
