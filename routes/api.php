<?php

use App\Http\Controllers\Api\AnioController;
use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\ConsultaMedicaController;
use App\Http\Controllers\Api\CostoFundacionController;
use App\Http\Controllers\Api\EnfermeroController;
use App\Http\Controllers\Api\EspecialidadeController;
use App\Http\Controllers\Api\ExamenController;
use App\Http\Controllers\Api\IngresosGastoController;
use App\Http\Controllers\Api\MedicamentoController;
use App\Http\Controllers\Api\MedicoController;
use App\Http\Controllers\Api\MesController;
use App\Http\Controllers\Api\PacienteConsultaController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\PsicopatologiaController;
use App\Http\Controllers\Api\SolicitudController;
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
//solicitudes
Route::resource('solicitudes', SolicitudController::class, ['except' => ['create', 'edit']]);
//consultas medicas
Route::resource('consultas-medicas', ConsultaMedicaController::class, ['except' => ['create', 'edit']]);
Route::put('consultas-medicas-finish/{id}', [ConsultaMedicaController::class,'finish']);
Route::get('consultas-medicas-user', [ConsultaMedicaController::class,'showByUser']);
Route::get('consultas-medicas-user-date/{date}', [ConsultaMedicaController::class,'showByUserDate']);

Route::get('consultas-medicas-medicamentos/{dpi}', [PacienteConsultaController::class,'getMedicamentos']);
Route::get('consultas-medicas-examenes/{dpi}', [PacienteConsultaController::class,'getExamenes']);

Route::put('consultas-medicas-entregar-medicamentos/{id}', [PacienteConsultaController::class,'entregarMedicamento']);
Route::post('consultas-medicas-resultado-examenes/{id}', [PacienteConsultaController::class,'resultadoExamen']);

//ingresos o gastos
Route::resource('ingresos-gastos', IngresosGastoController::class, ['except' => ['create', 'edit']]);

//costo fundaciones
Route::resource('pagos-fundacion', CostoFundacionController::class, ['except' => ['create', 'edit']]);

//pagos
Route::resource('pagos', PagoController::class, ['except' => ['create', 'edit']]);
Route::put('pagos-anular/{code}', [PagoController::class,'anular']);
Route::get('pagos-ascilo', [PagoController::class,'ascilo']);

//anios
Route::resource('anios', AnioController::class, ['except' => ['create', 'edit']]);

//meses
Route::resource('meses', MesController::class, ['except' => ['create', 'edit']]);
