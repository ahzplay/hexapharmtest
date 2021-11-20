<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountOutlet extends Model
{
    use HasFactory;

    public function outlet() {
        return $this->belongsTo(Outlet::class, 'kode_outlet','kode_outlet');
    }
}
