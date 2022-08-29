<?php

namespace App\Http\Resources;

use App\Models\Movements;
use Illuminate\Http\Resources\Json\JsonResource;

class getIngredientsReasource extends JsonResource
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
            "status"=>"ok",
            "id" => $this->id,
            "user_id_created" => $this->user_id_created,
            "date_created" => $this->date_created,
            "storage_id_from" => $this->storage_id_from,
            "storage_id_to" => $this->storage_id_to,
            "goods_id" => $this->goods_id,
            "amount" => $this->amount,
            "user_id_accepted" => $this->user_id_accepted,
            "price" => $this->price,
            "order_main" => $this->order_main,
            "date_accepted" => $this->date_accepted,
            "link_id" => $this->link_id,
            "category" => $this->category,
            "ingredients" => Movements::where('link_id', '=', $request->goods_id)->get()
        ];
    }
}
