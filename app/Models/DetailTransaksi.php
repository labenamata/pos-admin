<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use SoftDeletes;
    
    protected $table = 'detail_transaksi';
    
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'panjang',
        'lebar',
        'qty',
        'harga',
        'jumlah'
    ];
    
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
