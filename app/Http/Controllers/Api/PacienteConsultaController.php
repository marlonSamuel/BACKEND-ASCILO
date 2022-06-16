<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Consultas_medica;
use App\Models\Consultas_medicas_examene;
use App\Models\Paciente;
use App\Models\Solicitude;
use Illuminate\Http\Request;
use Illuminate\Http\storeAs;
use Illuminate\Support\Facades\DB;

class PacienteConsultaController extends ApiController
{
	public function __construct()
    {
        $this->middleware('auth:api');
    }

    //obtener medicamentos
    public function getMedicamentos($dpi)
    {
    	$paciente = Paciente::where('cui',$dpi)->first();

    	if(is_null($paciente))
    	{
    		return $this->errorResponse('Paciente no existe',404);
    	}

    	$consulta = Solicitude::where('paciente_id',$paciente->id)
    						->where('estado','F')->with('consulta.medicamentos.medicamento','consulta.medico','paciente','enfermero','especialidade')
    						->orderBy('fecha_visita')
    						->get();

    	return $this->showAll($consulta);
    }

    //obtener examenes
    public function getExamenes($dpi)
    {
    	$paciente = Paciente::where('cui',$dpi)->first();

    	if(is_null($paciente))
    	{
    		return $this->errorResponse('Paciente no existe',404);
    	}

    	$consulta = Solicitude::where('paciente_id',$paciente->id)
    						->where('estado','F')->with('consulta.examenes.examene','consulta.medico','paciente','enfermero','especialidade')
    						->orderBy('fecha_visita')
    						->get();

    	return $this->showAll($consulta);
    }

    //realizar examenes
    public function resultadoExamen(Request $request,$id)
    {
    	DB::beginTransaction();
    	$examen = Consultas_medicas_examene::where('id',$id)->with('consultas_medica','examene')->first();
    	$examen->realizado = true;
    	if(!is_null($request->resultado) && $request->resultado !== "" && $request->resultado !== "null"){
            $folder = 'resultados-examenes-'.$examen->consultas_medica->id;
            $name = $examen->id.'-'.$request->resultado->getClientOriginalName();              
            $examen->resultado = $request->resultado->storeAs($folder, $name);
        }

    	$examen->save();
    	DB::commit();
    	return $this->showOne($examen);
    }


    //entregar medicamentos
    public function entregarMedicamento($id)
    {
    	$consulta = Consultas_medica::find($id);
    	$consulta->medicamentos()->update(['entregado' => true]);
    	return $this->showOne($consulta);
    }
}
