<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['id_parent', 'name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_group');
    }

    public function subgroups()
    {
        return $this->hasMany(Group::class, 'id_parent');
    }

//    public function allProducts()
//    {
//        return $this->products()->with('subGroups.products')->get();
//    }

}
