<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Manage\BaseController;


class MethodsResource extends JsonResource
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
            'price' => $this->pivot ? $this->pivot->price : null,
            'name'=>$lang =='ar' ? $this->name_ar : $this->name_en,
            'image' => getImageUrl('Method',$this->image),
        ];
    }
}
