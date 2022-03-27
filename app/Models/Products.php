<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Product_images::class, 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cat()
    {
        return $this->belongsTo('App\Models\Categories','cat_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products_cuts()
    {
        return $this->belongsToMany(Cut_method::class,'product_cut_methods','product_id','cut_method_id')
            ->withPivot('price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products_packs()
    {
        return $this->belongsToMany(Pack_method::class,'product_pack_methods','product_id','pack_method_id')
            ->withPivot('price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usersFavourite(){
        return $this->belongsToMany(User::class,'wishlists','product_id','user_id');

    }
}
