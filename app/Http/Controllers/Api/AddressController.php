<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AddressResource;
use App\Interfaces\UserInterface;
use App\Models\User_addresses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Http\Resources\UserResource;
use App\Models\User;

class AddressController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function all(){
        return $this->apiResponseData(AddressResource::collection(Auth::user()->addresses),'',200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request){
        $User_addresses=User_addresses::where('user_id',Auth::user()->id)->where('id',$request->address_id)->firstOrFail();
        return $this->apiResponseData(new AddressResource($User_addresses),'',200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request){
        $validateUser_addresses=$this->validateUser_addresses($request);
        if(isset($validateUser_addresses))
            return $validateUser_addresses;
        $User_addresses=$this->saveData($request,new User_addresses());
        $msg=$request->header('lang') =='ar' ? 'تم حفظ العنوان بنجاح' : 'addresses saved successfully';
        return $this->apiResponseData(new AddressResource($User_addresses),$msg,200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request){
        $User_addresses=User_addresses::where('user_id',Auth::user()->id)->where('id',$request->address_id)->firstOrFail();
        $validateUser_addresses=$this->validateUser_addresses($request);
        if(isset($validateUser_addresses))
            return $validateUser_addresses;
        $User_addresses=$this->saveData($request,$User_addresses);
        $msg=$request->header('lang') =='ar' ? 'تم تعديل العنوان بنجاح' : 'addresses updated successfully';
        return $this->apiResponseData(new AddressResource($User_addresses),$msg,200);
    }

    /**
     * @param $request
     * @param $User_addresses
     * @return mixed
     */
    private function saveData($request,$User_addresses){
        $User_addresses->lat=$request->lat;
        $User_addresses->lng=$request->lng;
        $User_addresses->user_id=Auth::user()->id;
        $User_addresses->area_name=$request->area_name;
        $User_addresses->bldg_num=$request->bldg_num;
        $User_addresses->apt_num=$request->apt_num;
        $User_addresses->other_info=$request->other_info;
        $User_addresses->save();
        return $User_addresses;
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function validateUser_addresses($request)
    {
        $input = $request->all();
        $validationMessages = [
        ];

        $validator = Validator::make($input, [
            'lat' => 'required',
            'lng' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return $this->apiResponseMessage(0,$validator->messages()->first(), 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request){
        $User_addresses=User_addresses::where('user_id',Auth::user()->id)->where('id',$request->address_id)->firstOrFail();
        $User_addresses->delete();
        $msg=$request->header('lang') =='ar' ? 'تم حذف العنوان بنجاح' : 'User address deleted successfully';
        return $this->apiResponseMessage(1,$msg,200);
    }

}
