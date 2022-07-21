<?php

namespace App\Http\Resources;

use App\Models\Orders;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderInResource extends JsonResource
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
            'name' => 'Принять заявки',
            'in' => $inputOrder = Orders::all()->where('storage_id_to', '=',$request->id)->where('status', '=', null)->count(),
            'out' => $inputOrder = Orders::all()->where('storage_id_from', '=',$request->id)->where('status', '=', null)->count(),
            'progress' => $inputOrder = Orders::all()->where('storage_id_from', '=',$request->id)->where('status', '=', 'progress')->count(),
            'canceled' => $inputOrder = Orders::all()->where('storage_id_from', '=',$request->id)->where('status', '=', 'canceled')->count(),
        ];
    }
}
