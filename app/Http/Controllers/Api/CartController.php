<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Product_cut_method;
use App\Models\Product_pack_method;
use App\Models\Wishlist;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;

class CartController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    public function addToCart(Request $request){
        $validateCart=$this->validateCart($request);
        if(isset($validateCart))
            return $validateCart;
        $cart=$this->saveCart($request);
        $msg=$request->header('lang') =='ar' ? 'تمت العملية بنجاح' : 'operation accomplished successfully';
        return $this->apiResponseData(new CartResource($cart),$msg);
    }

    /**
     * @param $request
     * @return Cart
     */
    public function saveCart($request){
        $cart=Cart::where('user_id',Auth::user()->id)->where('product_id',$request->product_id)->where('is_order',2)->first();
        if(is_null($cart)) {
            $cart = new Cart();
            $cart->user_id=Auth::user()->id;
            $cart->product_id=$request->product_id;
        }
        $cart->type=$request->type;
        $cart->pack_method_id=$request->pack_method_id;
        $cart->cut_method_id=$request->cut_method_id;
        $cart->without=$request->without;
        $cart->quantity=$request->quantity;
        $cart->is_order=2;
        $cart->save();
        $this->calPrice($cart);
        return $cart;
    }

    /**
     * @param $cart
     */
    public function calPrice($cart){
        $quantity=$cart->quantity;
        $product=Products::find($cart->product_id);
        $productPrice=$product->price * $quantity;
        $methodsPrice=0;
        if($cart->type ==2){
          $cut_method=Product_cut_method::where('product_id',$cart->product_id)->where('cut_method_id',$cart->cut_method_id)
              ->value('price');
            $methodsPrice+=$cut_method* $quantity;
            $pack_method=Product_pack_method::where('product_id',$cart->product_id)->where('pack_method_id',$cart->pack_method_id)
                ->value('price');
            $methodsPrice+=$pack_method* $quantity;
        }
        $cart->methodsPrice=$methodsPrice;
        $cart->productPrice=$productPrice;
        $cart->totalPrice=$productPrice+ $methodsPrice;
        $cart->save();
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function validateCart($request)
    {
        $lang =  $request->header('lang') ;
        $input = $request->all();
        $validationMessages = [
            'product_id.required' => $lang == 'ar' ?  'من فضلك ادخل الاسم' :"name is required" ,
            'password.required' => $lang == 'ar' ? 'من فضلك ادخل كلمة السر' :"password is required"  ,
        ];

        $validator = Validator::make($input, [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:1,2',
        ], $validationMessages);

        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 2500);
        }
        $cut=Product_cut_method::where('product_id',$request->product_id)->where('cut_method_id',$request->cut_method_id)
            ->firstOrFail();
        $pack=Product_pack_method::where('product_id',$request->product_id)->where('pack_method_id',$request->pack_method_id)
            ->firstOrFail();

    }

}

