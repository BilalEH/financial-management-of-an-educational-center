<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbonnementResource;
use App\Http\Resources\SectionResource;
use App\Models\Etudiant;
use App\Models\facture;
use App\Models\section;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class PaiementController extends Controller
{

    public function index()
    {
        $allEtuSec = [];
        $etu = Etudiant::get();
        foreach ($etu as $ele) {
            if (!$ele->sections->isEmpty()) {
                $allEtuSec[] = $ele;
            };
        }
        return response()->json([
            'status' => true,
            'EtudiantInSection' => AbonnementResource::collection($allEtuSec),
        ]);
    }
    public function payer_facture(request $request, string $id)
    {
        $matere = section::find($id)->matere;
        $etudiant = Etudiant::find($request->etudiant_id);
        $calPrix = $matere->prix;

        if ($etudiant->pourcentage) {
            $calPrix = $matere->prix - (($etudiant->pourcentage * $matere->prix) / 100);
        };

        $lastFacDate = Carbon::parse($request->lastFac);
        $nextMonthDate = $lastFacDate->addMonths(1);

        $factureData = [
            'matere_id' => $matere->id,
            'etudiant_id' => $request->etudiant_id,
            'date_paye' => $request->date_paye,
            'prix' => $calPrix,
            'mois_facture' => Carbon::parse($nextMonthDate)->format('M Y')
        ];
        facture::create($factureData);


        section::find($id)->etudiants()->updateExistingPivot($request->etudiant_id, ['date_paye' => $nextMonthDate]);
        return response([
            'status' => true,
            'message' => 'La prestation a été réussie'
        ]);
    }
}
