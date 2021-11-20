<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function discountProduct()
    {
        return $this->hasOne(DiscountProduct::class, 'kode_produk', 'kode_produk');
    }
}
