<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getListGoodsMovementsOnStoragesReasource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->storage_id_from != null){
            $storageNameFrom = $this->storageFrom->name;
        }else{
            $storageNameFrom = null;
        }

        if ($this->storage_id_to != null){
            $storageNameTo = $this->storageTo->name;
        }else{
            $storageNameTo = null;
        }

        if ($this->user_id_accepted != null){
            $userAcceptedName = $this->userAccepted->name;
        }else{
            $userAcceptedName = null;
        }

        return [
            "id"=>$this->id,
            "user_id_created" => $this->user_id_created,
            "user_name_created" => $this->user->name,
            "date_created"=> $this->date_created,
            "storage_id_from" => $this->storage_id_from,
            "storage_name_from" => $storageNameFrom,
            "storage_id_to" => $this->storage_id_to,
            "storage_name_to" => $storageNameTo,
            "goods_id" => $this->goods_id,
            "goods_name" => $this->goods->name,
            "goods_unit" => $this->goods->unit,
            "amount" => $this->amount,
            "user_id_accepted" => $this->user_id_accepted,
            "user_name_accepted" => $userAcceptedName,
            "price" => $this->price,
            "order_main" => $this->order_main,
            "date_accepted" => $this->date_accepted,
            "link_id" => $this->link_id,
            "category" => $this->category,
        ];
    }
}
