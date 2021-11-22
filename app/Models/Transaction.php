<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasOne(Product::class, 'kode_produk', 'kode_produk');
    }

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'kode_produk', 'kode_produk');
    }
}
