<?php

namespace App\Reposatries;

use App\Http\Controllers\Api\CartController;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Validator, Auth, Artisan, Hash, File, Crypt;

class OrderRepo
{
    use \App\Traits\ApiResponseTrait;

    /**
     *
     * @param $request
     * @return Orders
     */
    public function saveOrder($request){
        $user=Auth::user();
        $order=new Orders();
        $order->user_id=$user->id;
        $order->address_id=$request->address_id;
        $order->paymentType=$request->paymentType;
        $order->code_id=$request->code_id;
        $order->status=1;
        $order->save();
        if($request->code_id !=0)
            handleCode(null,2);
        $this->saveProductsToOrder($order->id);
        $this->handelPrices($order);
        return  $order;
    }

    /**
     * @param $order_id
     */
    private function saveProductsToOrder($order_id){
        $user=Auth::user();
        $products=$user->myCart;
        foreach($products as $row){
            $row->is_order=1;
            $row->order_id=$order_id;
            $row->save();
        }
    }

    /**
     * @param $order
     */
    public function handelPrices($order){
        $user=Auth::user();
        $productPrice=$order->products->sum('totalPrice');
        $discount=$order->code_id ? calDiscountPrice($productPrice,$user->user_discount_value,$user->user_discount_type) : 0;
        $order->productPrice=$productPrice;
        $order->discountPrice=$discount;
        $order->taxPrice=getSetting()->taxPrice;
        $order->shippingPrice=getSetting()->shippingPrice;
        $order->totalPrice=getSetting()->taxPrice + getSetting()->shippingPrice + $productPrice - $discount;
        $order->save();
        // TODO :: wallet
    }

    /**
     * @param $request
     */
    public function updateCartQuantity($request){
        foreach($request->cart as $row){
            $cart=Cart::where('user_id',Auth::user()->id)->where('is_order',2)->where('id',$row['id'])
                ->first();
            $cart->quantity=$row['quantity'];
            $cart->save();
            $calPrice=new CartController();
            $calPrice->calPrice($cart);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function validateOrder($request)
    {
        $lang =  $request->header('lang');
        $request['cart'] = json_decode($request->cart, true);
        $user=Auth::user();
        $input = $request->all();
        $validationMessages = [
            'address_id.required' => $lang == 'ar' ? 'من فضلك ادخل العنوان' : "Address is required",
            'address_id.exists' => $lang == 'ar' ? 'العنوان المدخل غير صحيح' : "The address entered is incorrect",
        ];

        $validator = Validator::make($input, [
            'address_id' => 'required|exists:user_addresses,id,user_id,'.$user->id,
            'paymentType' => 'required|in:1,2',
            "cart.*.id" => 'required|exists:carts,id,user_id,'.$user->id.',is_order,2',
        ], $validationMessages);

        if ($validator->fails()) {
            return $this->apiResponseMessage(0, $validator->messages()->first(), 200);
        }
        if($user->myCart->count() ==0)
            return $this->apiResponseMessage(0,'your cart is empty',200);
        if($request->code_id !=0 AND $request->code_id != Auth::user()->code_id){
            $msg = $lang == 'en' ? 'discount code not found' : 'كود الخصم غير صحيح';
            return $this->apiResponseMessage(0, $msg, 200);
        }
    }

}
