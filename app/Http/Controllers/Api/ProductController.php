<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AddressResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SliderResource;
use App\Interfaces\UserInterface;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Slider;
use App\Models\User_addresses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Models\User;

class ProductController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function products(Request $request){
        $products=new Products;
        if($request->cat_id)
            $products=$products->where('cat_id',$request->cat_id);
        if($request->searchText) {
            $products = $products->where(function ($q)use ($request){
                $q->where('name_ar','LIKE','%'.$request->searchText.'%')
                    ->orWhere('name_en','LIKE','%'.$request->searchText.'%');
            });
        }
        if($request->sortPrice ==1)
            $products=$products->orderBy('price','asc');
        if($request->sortPrice ==2)
            $products=$products->orderBy('price','desc');
        $products=$products->paginate(10);
        return $this->apiResponseData(new ProductCollection($products),'');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function singleProduct(Request $request){
        $product=Products::findOrFail($request->product_id);
        return $this->apiResponseData(new ProductResource($product),'',200);
    }


}
