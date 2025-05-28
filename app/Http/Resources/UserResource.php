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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'last_login' => $this->last_login,
            'is_online' => $this->isOnline(),
            'banned_at' => $this->banned_at,
            'ban_reason' => $this->ban_reason,
            'joined_at' => $this->created_at,
        ];
    }
}
