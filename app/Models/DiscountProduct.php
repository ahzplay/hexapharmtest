<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    use HasFactory;

    public function product() {
        return $this->belongsTo(Product::class, 'kode_produk','kode_produk');
    }
}
