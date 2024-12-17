<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\ProblemCatalogController;
use App\Http\Controllers\ProblemTreatmentController;
use App\Http\Controllers\VisitController;
use App\Models\ProblemTreatment;

// Route::inertia('/login', 'Auth/Auth')->name('login');
// Route::post('/login', [AuthController::class, 'login']);

// Route::resource('/treatment', [TreatmentController::class]);

// Route::inertia('/treatment', function () {
//     return Inertia(
//         'Management/Treatment',
//         [Treatment::class]
//     );
// })->name('treatment');


Route::middleware('auth')->group(function () {
    // Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::get('/', function () {
        return Inertia('Home');
    })->name('Home');

    Route::get('/PatientProfile', function () {
        return Inertia('Management/PatientProfile');
    })->name('PatientProfile');

    Route::get('/PatientVisit', function () {
        return Inertia('Management/PatientVisit');
    })->name('PatientVisit');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(TreatmentController::class)->group(function () {
        Route::get('/treatments', 'index')->name('treatments');
        Route::post('/treatment', 'store')->name("treatment.store");
        Route::patch('/treatment/{treatment}', 'update')->name('treatment.update');
        Route::delete('/treatment/{treatment}', 'destroy')->name('treatment.delete');
    });

    Route::controller(ProblemCatalogController::class)->group(function () {
        Route::get('/problemCatalogs', 'index')->name('problemCatalogs');
        Route::post('/problemCatalog', 'store')->name("problemCatalog.store");
        Route::get('/problemCatalog/{id}', 'show')->name('problemCatalog.show');
        Route::get('/problemCatalog/{id}/edit', 'edit')->name('problemCatalog.edit');
        Route::patch('/problemCatalog/{problemCatalog}', 'update')->name('problemCatalog.update');
        Route::delete('/problemCatalog/{problemCatalog}', 'destroy')->name('problemCatalog.delete');
    });

    Route::controller(PatientController::class)->group(function () {
        Route::get('/patients', 'index')->name('patients');
        Route::post('/patient', 'store')->name("patient.store");
        Route::get('/patient/{id}', 'show')->name('patient.show');
        Route::get('/patient/{id}/edit', 'edit')->name('patient.edit');
        Route::patch('/patient/{patient}', 'update')->name('patient.update');
        Route::delete('/patient/{patient}', 'destroy')->name('patient.delete');
    });

    Route::controller(VisitController::class)->group(function () {
        Route::get('/visits', 'index')->name('visits');
        Route::post('/visit', 'store')->name("visit.store");
        Route::get('/PatientVisit/{id}', 'show')->name('PatientVisit');
        Route::get('/visit/{id}/edit', 'edit')->name('visit.edit');
        Route::patch('/visit/{visit}', 'update')->name('visit.update');
        Route::delete('/visit/{visit}', 'destroy')->name('visit.delete');
    });

    Route::controller(ProblemTreatmentController::class)->group(function () {
        Route::get('/problemTreatments', 'index')->name('problemTreatment');
        Route::post('/problemTreatment', 'store')->name("problemTreatment.store");
        Route::get('/problemTreatment/{id}', 'show')->name('problemTreatment.show');
        Route::get('/problemTreatment/{id}/edit', 'edit')->name('problemTreatment.edit');
        Route::patch('/problemTreatment/{problemTreatment}', 'update')->name('visit.update');
        Route::delete('/problemTreatment/{problemTreatment}', 'destroy')->name('visit.delete');
    });
});


Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
