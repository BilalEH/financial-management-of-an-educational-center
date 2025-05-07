<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data["etudiants"] = $this->etudiants;
        $data["matere_id"] = $this->matere->only('nom', 'id', 'prix');
        $data["niveau_id"] = $this->niveau->only('name', 'id');
        $data["professor_id"] = $this->professor->only('nom', 'prenom', 'telephone', 'id');
        if ($this->pivot) {
            $dateObject = Carbon::parse($this->pivot->date_paye);
            $dateObject->addMonth();
            $data["pivot"]['PROCHAINES_FACTURE'] = $dateObject->toDateString();
            $date1 = new DateTime($dateObject->toDateString());
            $date2 = new DateTime();
            $diff = $date2->diff($date1);
            $data["pivot"]['diffInDays'] = $diff->format('%R%a');
        }
        return $data;
    }
}
