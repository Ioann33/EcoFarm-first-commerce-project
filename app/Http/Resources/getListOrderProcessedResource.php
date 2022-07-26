<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getListOrderProcessedResource extends JsonResource
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
            'storage_id_to' => $this->storage_id_to,
            'storage_name_to' => $this->storageTo->name,
            'user_id_created' => $this->user_id_created,
            'user_name_created' => $this->user->name,
            'date_created' => $this->date_created,
            'user_id_handler'=>$this->user_id_handler,
            'user_name_handler'=>$this->handler->name,
            'date_status' => $this->date_created,
            'status' => $this->status,
        ];
    }
}
