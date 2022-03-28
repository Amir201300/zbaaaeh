<?php

/**
 * @return string
 */
function get_baseUrl()
{
    return url('/');
}

/**
 * @return mixed
 */
function get_user_lang()
{
    return Auth::user()->lang;
}

/**
 * @param $price
 * @return string
 */

function priceFormat($price){
    return number_format((float)$price, 2, '.', '');
}

/**
 * @param $date
 * @return false|string
 */
function CustomDateFormat($date){
    return date('m/d/Y', strtotime($date));
}

/**
 * @param $total_price
 * @param $discount
 * @param $discount_type
 * @return float|int
 */
function calDiscountPrice($total_price,$discount,$discount_type)
{
    $discount_value= $discount;
    if($discount_type==2) {
        $discount_value = ($total_price * $discount / 100);
    }
    return $discount_value;
}

/**
 * @param $price
 * @param $user
 */
function handleWallet($price,$user){
    $user->wallet+=$price;
    $user->save();
}

function handleCode($code,$check){
    $user=Auth::user();
    if($check == 2){
        $user->code_id=null;
        $user->user_discount_value=0;
        $user->user_discount_type=null;
        $user->save();
    }else{
        $user->code_id=$code->id;
        $user->user_discount_value=$code->amount;
        $user->user_discount_type=$code->amount_type;
        $user->save();
        return ['code_id'=>$code->id , 'discount_value'=>$code->amount,'discount_type'=>$code->amount_type];
    }
}

/**
 * @return mixed
 */
function getSetting(){
    return \App\Models\Setting::first();
}
