<?php

namespace App\Http\Controllers;

use App\Models\matere;
use App\Models\professor;
use Exception;
use Illuminate\Http\Request;

class professorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'data' => professor::get()
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'error' => $err
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'telephone' => ['required', 'string', 'unique:professors,telephone'],
            'date_debut' => ['required', 'date'],
            'pourcentage' => ['required', 'numeric']
        ]);
        $newProf = professor::create($request->only('nom', 'prenom', 'telephone', 'date_debut', 'pourcentage'));
        return response()->json([
            'status' => true,
            'professor' => $newProf
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                'status' => true,
                'professor' =>  professor::find($id)
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'error' => $err
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nom' => ['string'],
            'prenom' => ['string'],
            'telephone' => ['string'],
            'date_debut' => ['date'],
            'pourcentage' => ['numeric']
        ]);
        professor::find($id)->update($data);
        return response()->json([
            'status' => true,
            'id_professor' => $id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            professor::find($id)->delete();
            return response(['professor_id' => $id], 202);
        } catch (Exception $err) {
            return response(['error' => $err], 404);
        }
    }

    public function prof_sections(string $id)
    {
        $prof = professor::find($id);
        if ($prof) {
            return response()->json([
                'status' => true,
                'sections' => $prof->sections
            ]);
        } else {
            return response()->json([
                'status' => false,
                'sections' => 'professor exist pas'
            ]);
        }
    }


    public function Profs_total()
    {
        $profs = professor::get();
        $totals = [];
        foreach ($profs as $prof) {
            $total_conter = 0;
            $total_Prof = 0;
            $sections = $prof->sections;
            foreach ($sections as $section) {
                $matere = matere::find($section->matere_id);
                $etudiants = $section->etudiants;
                foreach ($etudiants as $etudiant) {
                    $prix_ma = $matere->prix;
                    if ($etudiant->pourcentage) {
                        $prix_ma = $matere->prix - (($matere->prix * $etudiant->pourcentage) / 100);
                    };
                    // $total += $prix_ma - (($prix_ma * $prof->pourcentage) / 100);
                    $total_Prof += number_format(($prix_ma * $prof->pourcentage) / 100, 2, '.', ' ');
                    $total_conter += $prix_ma - ($prix_ma * $prof->pourcentage) / 100;
                };
            };
            $totals[] = ['professor' => $prof->only('id', 'nom', 'prenom', 'telephone', 'date_debut', 'pourcentage'), 'totalProf' => $total_Prof, 'totalCenter' => $total_conter];
        }
        return response()->json([
            'status' => true,
            'totals' => $totals,
        ]);
    }

    public function center_boni_Profs()
    {
        $profs = professor::get();
        $totals = [];
        foreach ($profs as $prof) {
            $total = 0;
            $sections = $prof->sections;
            foreach ($sections as $section) {
                $matere = matere::find($section->matere_id);
                $etudiants = $section->etudiants;
                foreach ($etudiants as $etudiant) {
                    $prix_ma = $matere->prix;
                    if ($etudiant->pourcentage) {
                        $prix_ma = $matere->prix - (($matere->prix * $etudiant->pourcentage) / 100);
                    };
                    // $total += $prix_ma - (($prix_ma * $prof->pourcentage) / 100);
                };
            };
            $totals[] = ['professor' => $prof->only('id', 'nom', 'prenom', 'telephone', 'date_debut', 'pourcentage'), 'total' => $total];
        }
        return response()->json([
            'status' => true,
            'totals' => $totals,
        ]);
    }
}
