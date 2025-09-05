<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;
    
    protected $table = 'produk';
    
    protected $fillable = [
        'nama',
        'kategori_id',
        'satuan_id',
        'harga_pokok',
        'harga_jual'
    ];
    
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
