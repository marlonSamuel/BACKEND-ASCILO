<?php

use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\EnfermeroController;
use App\Http\Controllers\Api\EspecialidadeController;
use App\Http\Controllers\Api\ExamenController;
use App\Http\Controllers\Api\MedicamentoController;
use App\Http\Controllers\Api\MedicoController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\PsicopatologiaController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/logout', [AuthController::class, 'logout']);
Route::get('auth/me', [AuthController::class, 'user']);

//psicopagologias
Route::resource('psicopatologias', PsicopatologiaController::class, ['except' => ['create', 'edit']]);
//meedicamentos
Route::resource('medicamentos', MedicamentoController::class, ['except' => ['create', 'edit']]);
//examens
Route::resource('examenes', ExamenController::class, ['except' => ['create', 'edit']]);
//conceptos de pago
Route::resource('conceptos', ConceptoController::class, ['except' => ['create', 'edit']]);
//enfermeros
Route::resource('enfermeros', EnfermeroController::class, ['except' => ['create', 'edit']]);
//especialidades
Route::resource('especialidades', EspecialidadeController::class, ['except' => ['create', 'edit']]);
//medicos
Route::resource('medicos', MedicoController::class, ['except' => ['create', 'edit']]);
//pacientes
Route::resource('pacientes', PacienteController::class, ['except' => ['create', 'edit']]);