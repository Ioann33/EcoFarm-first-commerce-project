<?php

namespace App\Http\Resources;

use App\Models\Movements;
use App\Models\StockBalance;
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
        global $stockBalance;
        $totalPriceSum = $stockBalance
            ->where('storage_id', '=', $this->storage_id)
            ->where('goods_id','=', $this->goods_id)
            ->sum(function ($item){
                return $item->amount * $item->price;
            });

        $sumGoods = $stockBalance
            ->where('storage_id', '=', $this->storage_id)
            ->where('goods_id','=', $this->goods_id)
            ->sum('amount');

        if ($totalPriceSum != 0){
            $averagePrice = number_format($totalPriceSum / $sumGoods,2,'.','');
        }else{
            $averagePrice = 0;
        }




        return [
            'storage_name' => $this->storage_name,
            'storage_id'=> $this->storage_id,
            'goods_id' => $this->goods_id,
            'name' => $this->name,
            'unit' => $this->unit,
            'type' => $this->type,
            'amount' => $stockBalance
              ->where('storage_id', '=', $this->storage_id)
              ->where('goods_id','=', $this->goods_id)
              ->sum('amount'),
            'price' => $averagePrice,
            'goods' => $stockBalance
                ->where('storage_id', '=', $this->storage_id)
                ->where('goods_id','=', $this->goods_id),
        ];
    }
}
