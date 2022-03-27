<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'weight' => $this->weight,
            'age' => $this->age,
            'price' => priceFormat($this->price),
            'name'=>$lang =='ar' ? $this->name_ar : $this->name_en,
            'icon' => getImageUrl('Product',$this->icon),
            'cuttingMethods' => MethodsResource::collection($this->products_cuts),
            'packagesMethods' => MethodsResource::collection($this->products_packs),
        ];
    }
}
