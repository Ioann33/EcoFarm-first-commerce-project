<?php

namespace App\Http\Resources;

use App\Models\StorageGoods;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageGoodsPermitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permit = StorageGoods::where('storage_id', '=', $this->id)->where('goods_id', '=', $request->goods_id)->get('storage_id');
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
    }
}
