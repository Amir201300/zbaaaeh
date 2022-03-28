<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AddressResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SliderResource;
use App\Interfaces\UserInterface;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User_addresses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Models\User;

class GeneralController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function home(){
        $sliders=Slider::all();
        $cats=Categories::orderBy('id','desc')->get();
        $products=Products::orderBy('id','desc')->take(7)->get();
        $data=[
            'sliders'=>SliderResource::collection($sliders),
            'cats'=>CategoryResource::collection($cats),
            'products'=>ProductResource::collection($products)
        ];
        return $this->apiResponseData($data,'');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setting(){
        $setting=Setting::first();
        return $this->apiResponseData(new SettingResource($setting),'');
    }


}
