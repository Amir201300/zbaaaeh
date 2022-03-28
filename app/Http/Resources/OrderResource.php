<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
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
            'status' => (int)$this->status,
            'paymentType' => (int)$this->paymentType,
            'productPrice' => priceFormat($this->productPrice),
            'taxPrice' => priceFormat($this->taxPrice),
            'shippingPrice' => priceFormat($this->shippingPrice),
            'totalPrice' => priceFormat($this->totalPrice),
            'address' =>$this->address ?  new AddressResource($this->address) : null,
            'user' => new UserResource($this->user),
            'products' => CartResource::collection($this->products),
            'created_at'=>date('Y-m-d',strtotime($this->created_at)),
        ];
    }
}
