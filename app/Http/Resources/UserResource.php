<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Manage\BaseController;


class UserResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email,
            'name' => $this->name,
            'status' => (int)$this->status,
            'social'=>(int)$this->social,
            'password_code'=>(int)$this->password_code,
            'active_code'=>(int)$this->active_code,
            'wallet'=>$this->wallet,
            'image' => getImageUrl('User',$this->image),
            'token' => $this->user_token,
        ];
    }
}
