<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Manage\BaseController;


class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'without' => explode(',',$this->without),
            'type' => (int)$this->type,
            'pack_method_id' => (int)$this->pack_method_id,
            'cut_method_id' => (int)$this->cut_method_id,
            'quantity' => (int)$this->quantity,
            'productPrice' => priceFormat($this->productPrice),
            'methodsPrice' => priceFormat($this->methodsPrice),
            'totalPrice' => priceFormat($this->totalPrice),
            'product' => new ProductResource($this->product),
            'cutMethod' =>$this->cut ? new MethodsResource($this->cut) : null,
            'packMethod' =>$this->pack ? new MethodsResource($this->pack) : null,
        ];
    }
}
