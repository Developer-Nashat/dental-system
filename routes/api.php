<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProblemTreatmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\ProblemCatalogController;

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

Route::middleware('auth:sanctum')->group(function () {
    // Problem Treatments
    Route::get('/problem-treatments', [ProblemTreatmentController::class, 'index']);
    Route::post('/problem-treatments', [ProblemTreatmentController::class, 'store']);
    Route::get('/problem-treatments/{problemTreatment}', [ProblemTreatmentController::class, 'show']);
    Route::put('/problem-treatments/{problemTreatment}', [ProblemTreatmentController::class, 'update']);
    Route::delete('/problem-treatments/{problemTreatment}', [ProblemTreatmentController::class, 'destroy']);

    // Patients
    Route::get('/patients', [PatientController::class, 'index']);
    
    // Dentists (Users with dentist role)
    Route::get('/dentists', [UserController::class, 'getDentists']);
    
    // Treatments
    Route::get('/treatments', [TreatmentController::class, 'index']);
    
    // Problems
    Route::get('/problems', [ProblemCatalogController::class, 'index']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
