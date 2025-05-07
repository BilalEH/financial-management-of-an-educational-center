<?php

namespace App\Http\Controllers;

use App\Models\niveau;
use Exception;
use Illuminate\Http\Request;

class NiveauxController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'data' => niveau::get()
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'error' => $err
            ]);
        }
    }

    public function store(Request $request)
    {
        $valdData = $request->validate([
            'name' => ['required', 'string', 'unique:niveaux,name']
        ]);
        $newNiv = niveau::create($valdData);
        return response()->json([
            'status' => true,
            'niveau' => $newNiv
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
                'niveau' =>  niveau::find($id)
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
            'name' => ['string'],
        ]);
        niveau::find($id)->update($data);
        return response()->json([
            'status' => true,
            'id_niveau' => $id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            niveau::find($id)->delete();
            return response(['niveau_id' => $id], 202);
        } catch (Exception $err) {
            return response(['status' => false, 'error' => $err], 404);
        }
    }

    public function niveau_sections(string $id)
    {
        try {
            $niv = niveau::find($id);
            return response()->json([
                'status' => true,
                'sections' => $niv->sections
            ]);
        } catch (Exception $err) {
            return response(['status' => false, 'error' => $err], 404);
        }
    }
}
