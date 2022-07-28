<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getListOrderInResource extends JsonResource
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
          'order_id' => $this->id,
          'good_id' => $this->goods_id,
          'name' => $this->goods->name,
          'unit' => $this->goods->unit,
          'amount' => $this->amount,
          'storage_id_from' => $this->storage_id_from,
          'storage_name_from' => $this->storageFrom->name,
          'user_id_created' => $this->user_id_created,
          'user_name_created' => $this->user->name,
          'date_created' => $this->date_created,
          'status' => $this->status,
        ];
    }
}