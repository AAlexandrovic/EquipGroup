<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    public function prices()
    {
        return $this->hasOne(Price::class, 'id_product');
    }
}
