<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use SoftDeletes;
    
    protected $table = 'satuan';
    
    protected $fillable = [
        'nama'
    ];
    
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
