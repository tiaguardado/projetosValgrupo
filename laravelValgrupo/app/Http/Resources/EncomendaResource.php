<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EncomendaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tamanhoPizza' => $this->tamanhoPizza,
            'basePizza' => $this->basePizza,
            'ingredientesExtra' => $this->ingredientesExtra,
            'estadoPedido' => $this->estadoPedido,
            'created_at'=>$this->created_at,
        ];
    }
}
