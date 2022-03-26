<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use function GuzzleHttp\Psr7\str;


class AddressResource extends JsonResource
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
            'area_name' => $this->area_name,
            'bldg_num' => $this->bldg_num,
            'apt_num' =>$this->apt_num,
            'other_info' => $this->other_info,
            'lat' => (string)$this->lat,
            'lng' => (string)$this->lng,
        ];
    }
}
