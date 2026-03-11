<?php

use App\Http\Controllers\VesselController;
use App\Http\Controllers\ProspectController;
use Illuminate\Support\Facades\Route;

// ── Delivery Monitoring (POD Chasing) ──────────────────────────────────────
Route::get('/', [VesselController::class, 'index'])->name('vessels.index');
Route::post('/vessels', [VesselController::class, 'store'])->name('vessels.store');
Route::put('/vessels/{vessel}', [VesselController::class, 'update'])->name('vessels.update');
Route::delete('/vessels/{vessel}', [VesselController::class, 'destroy'])->name('vessels.destroy');
Route::patch('/vessels/{vessel}/quick', [VesselController::class, 'quickUpdate'])->name('vessels.quick');
Route::post('/vessels/{vessel}/pod', [VesselController::class, 'uploadPod'])->name('vessels.pod');
Route::get('/print', [VesselController::class, 'print'])->name('vessels.print');

// ── Prospects ──────────────────────────────────────────────────────────────
Route::resource('prospects', ProspectController::class);
Route::post('/prospects/{prospect}/create-delivery', [ProspectController::class, 'createDelivery'])
    ->name('prospects.createDelivery');
Route::patch('/prospects/{prospect}/quick-status', [ProspectController::class, 'quickStatus'])
    ->name('prospects.quickStatus');
Route::get('/prospects-export-pdf', [ProspectController::class, 'exportPdf'])
    ->name('prospects.exportPdf');
