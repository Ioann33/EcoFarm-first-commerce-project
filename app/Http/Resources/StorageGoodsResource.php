<?php

namespace App\Http\Resources;

use App\Models\Movements;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageGoodsResource extends JsonResource
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
          'amount' => $this->goods->movements->where('storage_id_to', '=', $this->storage_id)->where('user_id_accepted','!=', null)->sum('amount'),
        ];
    }
}
