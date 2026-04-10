<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'name',
        'tanggal',
        'price',
        'bayar',
        'kembalian',
        'status_member',
        'no_hp',
        'poin',
        'detail_produk',
    ];

}
