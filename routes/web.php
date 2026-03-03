<?php

use App\Http\Controllers\VesselController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VesselController::class, 'index'])->name('vessels.index');
Route::post('/vessels', [VesselController::class, 'store'])->name('vessels.store');
Route::put('/vessels/{vessel}', [VesselController::class, 'update'])->name('vessels.update');
Route::delete('/vessels/{vessel}', [VesselController::class, 'destroy'])->name('vessels.destroy');
Route::patch('/vessels/{vessel}/quick', [VesselController::class, 'quickUpdate'])->name('vessels.quick');
Route::post('/vessels/{vessel}/pod', [VesselController::class, 'uploadPod'])->name('vessels.pod');
Route::get('/print', [VesselController::class, 'print'])->name('vessels.print');
