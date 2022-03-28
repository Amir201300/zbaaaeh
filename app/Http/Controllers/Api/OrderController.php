<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Offer;
use App\Models\Orders;
use App\Models\UserDiscount;
use App\Reposatries\OrderRepo;
use App\Reposatries\RateReposatry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Auth, Artisan, Hash, File, Crypt;
use App\Models\User;

class OrderController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @param Request $request
     * @param OrderRepo $orderRepo
     * @return \App\Models\Order|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function makeOrder(Request $request,OrderRepo $orderRepo){
        $user=Auth::user();
        $validateOrder=$orderRepo->validateOrder($request);
        if(isset($validateOrder))
            return $validateOrder;
        $orderRepo->updateCartQuantity($request);
        $order=$orderRepo->saveOrder($request);
        return $this->apiResponseData(new OrderResource($order),'success',200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function applyDiscount(Request $request){
        $user=Auth::user();
        $lang=get_user_lang();
        $code=Discount::where('code',$request->code)->whereDate('expire_data','>=',now())->first();
        if(is_null($code)){
            $msg=$lang=='en' ? 'code not fount' : 'كود الخصم غير موجود';
            return $this->apiResponseMessage(0,$msg,200);
        }
        $is_use = UserDiscount::where('code_id',$code->id)->where('user_id',$user->id)
            ->first();
        if(!is_null($is_use)){
            $msg=$lang=='en' ?'code already used' : 'تم استخدام الكود'  ;
            return $this->apiResponseMessage(0,$msg,200);
        }
        $data= handleCode($code,1);
        $is_use=new UserDiscount();
        $is_use->user_id=$user->id;
        $is_use->code_id=$code->id;
        $is_use->save();
        $msg=$lang=='en' ? 'code apply successfully' : 'تم الخصم بنجاح';
        return $this->apiResponseData($data,$msg,200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeCode(){
        $user=Auth::user();
        $code=Discount::where('id',$user->code_id)->first();
        if(is_null($code)){
            $msg=get_user_lang()=='en' ? 'code not fount' : 'كود الخصم غير موجود';
            return $this->apiResponseMessage(0,$msg,200);
        }
        UserDiscount::where('code_id',$code->id)->where('user_id',$user->id)
            ->delete();
        handleCode(null,2);
        $msg=get_user_lang()=='en' ? 'code remove successfully' : 'تم حذف الكود بنجاح';
        return $this->apiResponseMessage(1,$msg,200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function myOrders(Request $request){
        $orders=Orders::where('user_id',Auth::user()->id)->orderBy('id','desc');
        if($request->status==1)
            $orders=$orders->whereIn('status',[1,2,3,4]);
        if($request->status==2)
            $orders=$orders->where('status',5);
        $orders=$orders->paginate(20);
        return $this->apiResponseData(new OrderCollection($orders),'');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function singleOrder(Request $request){
        $order=Orders::where('user_id',Auth::user()->id)->where('id',$request->order_id)->firstOrFail();
        return $this->apiResponseData(new OrderResource($order),'',200);

    }

}
