<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CreatorResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "image" => asset("images/posts/images/".$this->image),
            "createdAt" => $this->createdAt,
            "updated_at" => $this->updated_at,
            "creator_id" => $this->creator_id,
            "description" => $this-> description,
            "author_id" => $this->author_id,
            // "creator" => $this->creator ? $this->creator->name : null,
            "creator" => new CreatorResource($this->creator),
        ];
    }
}
