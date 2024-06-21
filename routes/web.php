<?php

use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PatientController::class, 'index'])->name('dashboard');
    Route::get('/patients/{patient}/info', [PatientController::class, 'info'])->name('patient.info');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patient.update');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('patient.destroy');

    Route::get('patients/{patient}/documents/{folder?}', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('patients/{patient}/documents/{folder?}/folder', [DocumentController::class, 'storeFolder'])->name('folders.store');
    Route::post('patients/{patient}/documents/{folder?}/document', [DocumentController::class, 'storeDocument'])->name('documents.store');
    Route::delete('patients/{patient}/documents/destroy/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::delete('patients/{patient}/documents/folder/destroy/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::get('patients/{patient}/documents', [PatientController::class, 'documents'])->name('patient.documents');
    Route::put('patients/{patient}/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::put('patients/{patient}/documents/folder/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::get('patients/{patient}/documents/{folder}/download-zip', [DocumentController::class, 'downloadZip'])->name('folders.downloadZip');
    //Route::get('documents/{document}/download', [DocumentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('patients/{patient}/documents/{folder}/{document}/download', [DocumentController::class, 'download'])->name('documents.download');


    Route::get('patients/{patient}/sessions', [PatientSessionController::class, 'index'])->name('sessions.index');
    Route::get('patients/sessions/create', [PatientSessionController::class, 'create'])->name('sessions.create');
    Route::post('patients/sessions', [PatientSessionController::class, 'store'])->name('sessions.store');
    Route::get('patients/sessions/{session}/edit', [PatientSessionController::class, 'edit'])->name('sessions.edit');
    Route::put('patients/{patient}/sessions/{session}', [PatientSessionController::class, 'update'])->name('sessions.update');
    Route::delete('patients/{patient}/sessions/{session}', [PatientSessionController::class, 'destroy'])->name('sessions.destroy');
    Route::post('patients/{patient}/sessions/deleteAll', [PatientSessionController::class, 'deleteAll'])->name('sessions.deleteAll');
    Route::get('patients/{patient}/sessions/{session}/export', [PatientSessionController::class, 'export'])->name('sessions.export');
    Route::get('patients/{patient}/sessions/{session}', [PatientSessionController::class, 'show'])->name('sessions.show');


    Route::resource('diagnoses', DiagnosisController::class);

    Route::get('/patients/{patient}/export', [PatientController::class, 'export'])->name('patient.export');





});


require __DIR__.'/auth.php';
