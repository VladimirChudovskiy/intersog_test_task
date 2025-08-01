<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AuthorResource",
 *     title="Author Resource",
 *     description="Author resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="F. Scott Fitzgerald"),
 *     @OA\Property(property="books_count", type="integer", example=5)
 * )
 */
class AuthorResource extends JsonResource
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
            'name' => $this->name,
            'books_count' => $this->when(isset($this->books_count), $this->books_count),
        ];
    }
}
