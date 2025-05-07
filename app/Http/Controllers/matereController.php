<?php

namespace App\Http\Controllers;

use App\Models\matere;
use Exception;
use Illuminate\Http\Request;

class matereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => matere::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'unique:materes,nom'],
            'prix' => ['required', 'numeric']
        ]);
        $newMatere = matere::create($request->only('nom', 'prix'));

        return response()->json([
            'status' => true,
            'data' => $newMatere
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
                'matere' => matere::find($id)->only('id', 'nom', 'prix')
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'Errors' => $err
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
            'prix' => ['numeric']
        ]);
        matere::find($id)->update($data);
        return response()->json([
            'status' => true,
            'id_matere' => $id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            matere::find($id)->delete();
            return response(['matere_id' => $id], 202);
        } catch (Exception $err) {
            return response(['error' => $err], 404);
        }
    }

    public function matere_sections(string $id)
    {
        $matere = matere::find($id);
        if ($matere) {
            return response()->json([
                'status' => true,
                'sections' => $matere->sections,
                'matere' => $matere->only('id', 'nom', 'prix')
            ]);
        }
        return response()->json([
            'status' => false,
            'Error' => 'id matere exist pas',
        ]);
    }
    public function matere_factures(string $id)
    {
        $matere = matere::find($id);
        if ($matere) {
            return response()->json([
                'status' => true,
                'factures' => $matere->factures,
                'matere' => $matere->only('id', 'nom', 'prix')
            ]);
        }
        return response()->json([
            'status' => false,
            'Error' => 'id matere exist pas',
        ]);
    }
}
