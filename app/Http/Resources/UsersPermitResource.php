<?php

namespace App\Http\Resources;

use App\Models\StorageGoods;
use App\Models\UserStorages;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersPermitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $permit = UserStorages::
        where('storage_id', '=', $this->id)
            ->where('user_id', '=', $request->user_id)
            ->get('storage_id');

        if (isset($permit[0]['storage_id'])){
            $allow = true;
        }else{
            $allow = false;
        }
        return [
            'storage_id'=>$this->id,
            'storage_name'=>$this->name,
            'allowed'=>$allow
        ];

//        return [
//            'storage_id'=>$this->storage_id,
//        ];
    }
}
