<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\facture;
use App\Models\matere;
use App\Models\niveau;
use App\Models\professor;
use App\Models\section;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatistiquesController extends Controller
{
    public function cuntEles()
    {
        $count_etudiants = Etudiant::count();
        $count_Profs = professor::count();
        $count_materes = matere::count();
        $count_niveaux = niveau::count();
        $count_sections = section::count();
        // $count_ = Etudiant::count();

        return response()->json([
            'status' => true,
            'etudiants' => $count_etudiants,
            'professors' => $count_Profs,
            'materes' => $count_materes,
            'niveaux' => $count_niveaux,
            'sections' => $count_sections,
        ]);
    }

    public function factureStaDiagrameData()
    {
        $factures = facture::get();
        $monthlyTotals = $factures->groupBy(function ($facture) {
            return Carbon::parse($facture->date_paye)->format('M Y');
        })->map(function ($group) {
            return $group->sum('prix');
        });

        $monthlyTotals = $monthlyTotals->sortBy(function ($total, $month) {
            return Carbon::parse($month)->timestamp;
        });

        $months = $monthlyTotals->keys()->toArray();
        $totals = $monthlyTotals->values()->toArray();

        return response()->json([
            'months' => $months,
            'totals' => $totals,
        ]);
    }
    public function getTotalFacturePrixThisMonth()
    {
        $currentMonth = now()->format('Y-m');
        $totalPrixThisMonth = Facture::whereYear('date_paye', now()->year)
            ->whereMonth('date_paye', now()->month)
            ->sum('prix');
        return response()->json([
            'month' => $currentMonth,
            'total' => $totalPrixThisMonth,
        ]);
    }

    public function getEtudiantParSection()
    {
        $sections = section::get();
        $res = [];
        foreach ($sections as $section) {
            $res[] = ['label' => $section->nom, 'value' => $section->etudiants->count()];
        }

        return response()->json([
            'state' => true,
            'data' => $res
        ]);
    }
}
