<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getMovementResourceReview extends JsonResource
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
            $storageName = $this->storage_from_name;
        }else{
            $storageID = $this->storage_id_to;
            $storageName = $this->storage_to_name;
        }

        return [
            'id'=>$this->id,
            'user_id_created' => $this->user_id_created,
            'user_name_created' => $this->user_name_created,
            'date_created' => $this->date_created,
            'storage_id' => $storageID,
            'storage_name' => $storageName,
            'goods_id' => $this->goods_id,
            'goods_name' => $this->name,
            'goods_type' => $this->type,
            'unit' => $this->unit,
            'amount' => $this->amount,
            'user_id_accepted' => $this->user_id_accepted,
            'user_name_accepted' => $this->user_name_accepted,
            'price' => $this->price,
            'order_main'=>$this->order_main,
            'date_accepted' => $this->date_accepted,
            'link_id' => $this->link_id,
            'category' => $this->category
        ];
    }
}
