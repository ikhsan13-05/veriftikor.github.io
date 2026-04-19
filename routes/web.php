<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MapsController;


Route::get('/', [StudentController::class, 'index'])->name('students.index');

Route::get('/verify/{id}', [StudentController::class, 'verifyForm'])->name('students.verify');

Route::post('/verify', [StudentController::class, 'verify'])->name('verify.nisn');

Route::get('/maps', [MapsController::class, 'index'])->name('maps.index');

Route::post('/maps', [MapsController::class, 'store'])->name('maps.store');