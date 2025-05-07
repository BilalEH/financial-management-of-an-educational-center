<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectionResource;
use App\Models\Etudiant;
use App\Models\facture;
use App\Models\section;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'statue' => true,
            'sections' => SectionResource::collection(section::get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:30', 'unique:sections,nom'],
            'matere_id' => ['required', 'exists:materes,id'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'professor_id' => ['required', 'exists:professors,id'],
        ]);
        $newSec = section::create($data);
        return response()->json([
            'status' => true,
            'new_section' => new SectionResource($newSec)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Sec = section::find($id);
        return response()->json([
            'status' => true,
            'section' => new SectionResource($Sec),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:30'],
            'matere_id' => ['required', 'exists:materes,id'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'professor_id' => ['required', 'exists:professors,id'],
        ]);
        section::find($id)->update($data);
        $after = section::find($id);
        return response()->json([
            'status' => true,
            'section_after' => new SectionResource($after),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            section::find($id)->delete();
            return response(['section_id' => $id], 202);
        } catch (Exception $err) {
            return response(['error' => $err], 404);
        }
    }

    public function Add_Etudiant_In_Section(Request $request, String $id)
    {
        $request->validate([
            'etudiant_id' => ['required', 'exists:etudiants,id'],
            'date_inscription' => ['required', 'date'],
        ]);
        if (section::find($id)->etudiants()->find($request->etudiant_id)) {
            return response()->json([
                'status' => false,
                'message' => 'etudiant deja on section'
            ]);
        } else {
            section::find($id)->etudiants()->attach([$request->etudiant_id => ['date_inscription' => $request->date_inscription, 'date_paye' => $request->date_inscription]]);
            // --------------add facture------------------
            $matere = section::find($id)->matere;
            $etudiant = Etudiant::find($request->etudiant_id);

            if ($etudiant->pourcentage) {
                $calPrix = $matere->prix - (($etudiant->pourcentage * $matere->prix) / 100);
                $factureData = [
                    'matere_id' => $matere->id,
                    'etudiant_id' => $request->etudiant_id,
                    'date_paye' => $request->date_inscription,
                    'prix' => $calPrix,
                    'mois_facture' => Carbon::parse($request->date_inscription)->format('M Y')
                ];
                facture::create($factureData);
            } else {
                $factureData = [
                    'matere_id' => $matere->id,
                    'etudiant_id' => $request->etudiant_id,
                    'date_paye' => $request->date_inscription,
                    'prix' => $matere->prix,
                    'mois_facture' => Carbon::parse($request->date_inscription)->format('M Y')
                ];
                facture::create($factureData);
            }
            // --------------------------------
            return response()->json(
                [
                    'status' => true,
                    'section_id' => $id,
                    'etudiants' => section::find($id)->etudiants,
                ]
            );
        }
    }
    public function Remove_Etudiant_In_Section(Request $request, String $id)
    {
        $request->validate([
            'etudiant_id' => ['required', 'exists:etudiants,id']
        ]);
        section::find($id)->etudiants()->detach($request->etudiant_id);
        return response()->json(
            [
                'status' => true,
                'section_id' => $id,
                'etudiants' => section::find($id)->etudiants
            ]
        );
    }

    public function Section_etudiants(String $id)
    {
        $section = section::find($id);
        if ($section) {
            return response()->json(
                [
                    'status' => true,
                    'section_id' => $id,
                    'etudiants' => $section->etudiants,
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'section_id' => $id,
                    'error' => 'section exist pas'
                ]
            );
        }
    }
}
