<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JsSdkResource extends JsonResource
{

    public function toArray($request)
    {
        return $request;
    }
}