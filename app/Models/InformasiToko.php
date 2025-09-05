<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformasiToko extends Model
{
    use SoftDeletes;
    
    protected $table = 'informasi_toko';
    
    protected $fillable = [
        'nama_toko',
        'alamat',
        'no_telepon',
        'email',
        'logo'
    ];
}
