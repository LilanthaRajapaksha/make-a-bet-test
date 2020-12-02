<?php

namespace App\Http\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleTransformer extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
