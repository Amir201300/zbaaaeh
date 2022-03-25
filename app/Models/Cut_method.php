<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cut_method extends Model
{
    public function products_cut()
    {
        return $this->belongsToMany(Products::class,'product_cut_methods','cut_method_id','product_id');
    }
}
