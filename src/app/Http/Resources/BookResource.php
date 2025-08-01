<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="BookResource",
 *     title="Book Resource",
 *     description="Book resource",
 *     @OA\Property(property="title", type="string", example="The Great Gatsby"),
 *     @OA\Property(property="description", type="string", example="A novel by F. Scott Fitzgerald"),
 *     @OA\Property(property="authors", type="array", @OA\Items(ref="#/components/schemas/AuthorResource")),
 *     @OA\Property(property="published_date", type="string", format="date-time", example="2021-01-01T00:00:00.000000Z")
 * )
 */
class BookResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->short_description ?? $this->long_description,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'published_date' => $this->published_date,
        ];
    }
}
