<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Models\Wishlist;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;

class FavouriteController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    public function addOrRemove(Request $request){
        $user=Auth::user();
        $book = Products::where('id', $request->product_id)->first();
        if (is_null($book)) {
            $msg = get_user_lang() == 'en' ? 'Products not found' : 'المنتج غير موجودة';
            return $this->apiResponseMessage(0, $msg, 200);
        }
        $fav = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
        if (is_null($fav)) {
            $fav = new Wishlist();
            $fav->user_id = $user->id;
            $fav->product_id = $request->product_id;
            $fav->save();
            $msg = get_user_lang() == 'en' ? 'Products added to your Favorite' : 'تم اضافة المنتج الي مفضلاتك';
            return $this->apiResponseMessage(1, $msg, 200);
        }
        $fav->delete();
        $msg = get_user_lang() == 'en' ? 'Products deleted from your Favorite' : 'تم حذف المنتج من مفضلاتك';
        return $this->apiResponseMessage(1, $msg, 200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function myFavourite(){
        return $this->apiResponseData(ProductResource::collection(Auth::user()->myWishlist),'',200);
    }

}

