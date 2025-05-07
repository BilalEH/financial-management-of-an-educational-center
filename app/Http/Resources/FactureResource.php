<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data["matere_id"] = $this->matere;
        $data["etudiant_id"] = $this->etudiant;
        // $data["section"] = new SectionResource($this->etudiant->sections()->where(['matere_id' => $this->matere_id])->first());
        return $data;
    }
}
