<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'image_url' => $this->image ?: null,
            'images' => $this->images,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'published' => (bool)$this->published,
            'categories' => $this->categories->map(fn($c) => $c->id),
            'created_at' => (new \DateTime($this->created_at))->format('d-m-Y H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('d-m-Y H:i:s'),
        ];
    }
}
