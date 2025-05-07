<?php

namespace App\Http\Controllers;

use App\Http\Resources\FactureResource;
use App\Models\etudiant;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;

class EtudientController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'etudients' => Etudiant::get()
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:30'],
            'prenom' => ['required', 'string', 'max:30'],
            'telephone' => ['required', 'string', 'unique:etudiants,telephone'],
        ]);
        if (is_numeric($request->pourcentage) and $request->pourcentage > 0 and $request->pourcentage <= 100) {
            $newEtu = Etudiant::create($request->only('nom', 'prenom', 'telephone', 'pourcentage'));
        } else {
            $newEtu = Etudiant::create($request->only('nom', 'prenom', 'telephone'));
        }
        return response()->json([
            'status' => true,
            'new_etudiant' => $newEtu
        ]);
    }
    public function show(string $id)
    {
        $etu = Etudiant::find($id);
        return response()->json([
            'status' => true,
            'data' => $etu,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nom' => ['string', 'max:30'],
            'prenom' => ['string', 'max:30'],
            'telephone' => ['string'],
            'pourcentage' => ['nullable', 'numeric', 'min:1', 'max:100']
        ]);
        if (!$request->pourcentage) {
            $request['pourcentage'] = null;
            Etudiant::find($id)->update($request->only('nom', 'prenom', 'telephone', 'pourcentage'));
        }
        Etudiant::find($id)->update($request->only('nom', 'prenom', 'telephone', 'pourcentage'));
        return response()->json([
            'status' => true,
            'id_etudiant' => $id,
        ]);
    }

    public function destroy(string $id)
    {
        try {
            Etudiant::find($id)->delete();
            return response(['etudiant_id' => $id], 202);
        } catch (Exception $err) {
            return response(['error' => $err], 404);
        }
    }

    public function etudiant_section(string $id)
    {
        $etu = etudiant::find($id);
        return response()->json([
            'status' => true,
            'sections' => $etu->sections,
            'etudiant' => $etu->only('id', 'nom', 'prenom', 'telephone')
        ]);
    }
    public function etudiant_factures(string $id)
    {
        $etu = etudiant::find($id);
        return response()->json([
            'status' => true,
            'factures' => $etu->factures->sortBy('date_paye'),
            'etudiant' => $etu->only('id', 'nom', 'prenom', 'telephone', 'pourcentage')
        ]);
    }

    public function etudiant_last_facture(string $id)
    {
        $etu = etudiant::find($id);
        $fac = new FactureResource($etu->factures()->latest('date_paye')->first());
        return response()->json([
            'status' => true,
            'last_facture' => $fac,
        ]);
    }
}
