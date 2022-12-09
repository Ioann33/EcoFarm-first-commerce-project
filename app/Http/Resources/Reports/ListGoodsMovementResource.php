<?php

namespace App\Http\Resources\Reports;

use App\Models\Movements;
use Illuminate\Http\Resources\Json\JsonResource;

class ListGoodsMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr =  [
            "id"=>$this->id,
            "user_id_created" => $this->user_id_created,
            "user_name_created" => $this->user_name_created,
            "date_created"=> $this->date_created,
            "storage_id_from" => $this->storage_id_from,
            "storage_name_from" => $this->storage_from_name,
            "storage_id_to" => $this->storage_id_to,
            "storage_name_to" => $this->storage_to_name,
            "goods_id" => $this->goods_id,
            "goods_name" => $this->name,
            "goods_unit" => $this->unit,
            "amount" => $this->amount,
            "user_id_accepted" => $this->user_id_accepted,
            "user_name_accepted" => $this->user_name_accepted,
            "price" => $this->price,
            "order_main" => $this->order_main,
            "date_accepted" => $this->date_accepted,
            "link_id" => $this->link_id,
            "category" => $this->category,
            "goods_type" => $this->type
        ];

        if($this->category == "ready") {
            // получить цену отгрузки price = link_id + move
            $cost_with_production = Movements::where('link_id', '=', $this->link_id)->where('category', '=', 'move')->first();
            if(!is_null($cost_with_production)) {
                $arr["cost_with_production"] = $cost_with_production->price;
                $arr["prime_cost"] = $this->price;
                if($cost_with_production->user_id_accepted !== null )
                    $arr["goodsReady_is_accepted"] = "yes";
                else
                    $arr["goodsReady_is_accepted"] = "no";

            }
            else
                $arr["price_with_production"] = null;
        }






        return $arr;
    }
}
