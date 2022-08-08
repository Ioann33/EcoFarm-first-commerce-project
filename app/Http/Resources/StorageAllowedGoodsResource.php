<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StorageAllowedGoodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'goods_id' => $this->goods_id,
            'name' => $this->goods->name,
            'unit' => $this->goods->unit,
            'type' => $this->goods->type,
        ];
    }
}
