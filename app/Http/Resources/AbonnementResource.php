<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbonnementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        // $matere_id = $this->matere;
        // $niveau_id = $this->niveau;
        // $professor_id = $this->professor;
        $data['sections'] = SectionResource::collection($this->sections);
        return $data;
    }
}
