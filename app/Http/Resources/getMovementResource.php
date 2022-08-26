<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->dir === 'in'){
            $storageID = $this->storage_id_from;
            $storageName = $this->storageFrom;
        }else{
            $storageID = $this->storage_id_to;
            $storageName = $this->storageTo;
        }

        return [
            'id'=>$this->id,
            'user_id_created' => $this->user_id_created,
            'user_name_created' => $this->user->name,
            'date_created' => $this->date_created,
            'storage_id' => $storageID,
            'storage_name' => $storageName->name,
            'goods_id' => $this->goods_id,
            'goods_name' => $this->goods->name,
            'goods_type' => $this->goods->type,
            'unit' => $this->goods->unit,
            'amount' => $this->amount,
            'user_id_accepted' => $this->user_id_accepted,
            'user_name_accepted' => $this->userAccepted->name,
            'price' => $this->price,
            'order_main'=>$this->order_main,
            'date_accepted' => $this->date_accepted,
            'link_id' => $this->link_id,
            'category' => $this->category
        ];
    }
}
