<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    public function discountOutlet()
    {
        return $this->hasOne(DiscountOutlet::class, 'kode_outlet', 'kode_outlet');
    }
}
