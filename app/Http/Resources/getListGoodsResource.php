<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getListGoodsResource extends JsonResource
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
            'goods_id' => $this->id,
            'name' => $this->name,
            'unit' => $this->unit,
            'type' => $this->type
        ];
    }
}
