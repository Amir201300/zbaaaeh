<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack_method extends Model
{
    public function products_pack()
    {
        return $this->belongsToMany(Products::class,'product_pack_methods','pack_method_id','product_id');
    }
}
