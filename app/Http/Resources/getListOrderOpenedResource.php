<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getListOrderOpenedResource extends JsonResource
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
            $storageName = $this->storageTo;
        }
        return [
          'order_id' => $this->id,
          'good_id' => $this->goods_id,
          'name' => $this->goods->name,
          'unit' => $this->goods->unit,
          'amount' => $this->amount,
          'storage_id' => $storageID,
          'storage_name' => $storageName->name,
          'user_id_created' => $this->user_id_created,
          'user_name_created' => $this->user->name,
          'date_created' => $this->date_created,
          'status' => $this->status,
        ];
    }
}
