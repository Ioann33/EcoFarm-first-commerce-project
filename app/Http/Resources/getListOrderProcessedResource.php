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

        if($request->dir === 'in'){
            $storageID = $this->storage_id_from;
            $storageName = $this->storageFrom;
        }else{
            $storageID = $this->storage_id_to;
            $storageName = $this->storageFrom;
        }

        return [
            'order_id' => $this->id,
            'goods_id' => $this->goods_id,
            'name' => $this->goods->name,
            'unit' => $this->goods->unit,
            'amount' => $this->amount,
            'storage_id' => $storageID,
            'storage_name' => $storageName->name,
            'user_id_created' => $this->user_id_created,
            'user_name_created' => $this->user->name,
            'date_created' => $this->date_created,
            'user_id_handler'=>$this->user_id_handler,
            'user_name_handler'=>$this->handler->name,
            'date_status' => $this->date_status,
            'status' => $this->status,
        ];
    }
}
