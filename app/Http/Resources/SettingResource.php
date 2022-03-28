<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang=$request->header('lang');
        return [
            'id' => $this->id,
            'shippingPrice' => $this->shippingPrice,
            'taxPrice' => $this->taxPrice,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'snap' => $this->snap,
            'phone' => $this->phone,
            'about' =>$lang=='ar' ? $this->about_ar : $this->about_en,
        ];
    }
}
