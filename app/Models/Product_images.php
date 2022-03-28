<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_images extends Model
{

    public function Pro_image()
    {
        return $this->belongsTo('App\Models\Products','product_id');
    }
}
