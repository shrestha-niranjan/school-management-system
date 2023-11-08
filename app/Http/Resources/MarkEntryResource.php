<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_name' => $this->user->name,
                    'course' => 'englisth',
                    'external' => 12,
                    'internal' => 22,
                    'total' => 10
        ];
    }
}
