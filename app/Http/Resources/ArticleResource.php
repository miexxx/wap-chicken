<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'thumb_url' => $this->thumb_url,
            'scope' => $this->scope_info,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'update_time' => date('Y-m-d H:i:s',$this->article->update_time),
        ];
    }
}