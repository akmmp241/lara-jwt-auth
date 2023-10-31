<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "createdAt" => $this->created_at->format("d-m-Y H:i:s"),
            "updatedAt" => $this->updated_at->format("d-m-Y H:i:s"),
            "emailVerifiedAt" => $this->email_verified_at
                ? $this->email_verified_at->format("d-m-Y H:i:s")
                : null,
        ];
    }
}
