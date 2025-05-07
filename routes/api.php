<?php

use App\Http\Controllers\PaiementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EtudientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\matereController;
use App\Http\Controllers\NiveauxController;
use App\Http\Controllers\professorController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StatistiquesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return ['test' => 'Test'];
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);
Route::group([
    'middleware' => ['auth:api']
], function () {
    // ------------- Login --------------
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('refresh', [ApiController::class, 'refreshToken']);
    Route::get('logout', [ApiController::class, 'logout']);
    // ------------- etudiants --------------
    Route::apiResource('etudiants', EtudientController::class);
    Route::get('/etudiants/sections/{id}', [EtudientController::class, 'etudiant_section']);
    Route::get('/etudiants/factures/{id}', [EtudientController::class, 'etudiant_factures']);
    Route::get('/etudiants/last_facture/{id}', [EtudientController::class, 'etudiant_last_facture']);
    // ------------- materes --------------
    Route::apiResource('materes', matereController::class);
    Route::get('/materes/sections/{id}', [matereController::class, 'matere_sections']);
    Route::get('/materes/factures/{id}', [matereController::class, 'matere_factures']);
    // ------------- professors --------------
    Route::apiResource('professors', professorController::class);
    Route::get('/professors/sections/{id}', [professorController::class, 'prof_sections']);
    Route::get('/professor/prof_total', [professorController::class, 'Profs_total']);
    Route::get('/professor/centerBoniProfs', [professorController::class, 'center_boni_Profs']);
    // ------------- niveaux --------------
    Route::apiResource('niveaux', NiveauxController::class);
    Route::get('/niveaux/sections/{id}', [NiveauxController::class, 'niveau_sections']);
    // ------------- sections --------------
    Route::apiResource('sections', SectionController::class);
    Route::post('/sections/addEtu/{id}', [SectionController::class, 'Add_Etudiant_In_Section']);
    Route::post('/sections/removeEtu/{id}', [SectionController::class, 'Remove_Etudiant_In_Section']);
    Route::get('/sections/etudiants/{id}', [SectionController::class, 'Section_etudiants']);
    // ------------- Factures --------------
    Route::apiResource('factures', FactureController::class);
    // ------------- Paiements --------------
    Route::apiResource('paiements', PaiementController::class);
    Route::post('/paiements/payerfacture/{id}', [PaiementController::class, 'payer_facture']);
    Route::get('/paiement/notpayed', [PaiementController::class, 'Not_pay_etu']);
    // ------------- statistiques --------------
    Route::get('/statistiques/cuntEles', [StatistiquesController::class, 'cuntEles']);
    Route::get('/statistiques/factureSta', [StatistiquesController::class, 'factureStaDiagrameData']);
    Route::get('/statistiques/factureStaThisMonth', [StatistiquesController::class, 'getTotalFacturePrixThisMonth']);
    Route::get('/statistiques/getEtudiantParSection', [StatistiquesController::class, 'getEtudiantParSection']);
});
