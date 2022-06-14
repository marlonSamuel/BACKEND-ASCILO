<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Consultas_medica;
use App\Models\Consultas_medicas_examene;
use App\Models\Consultas_medicas_medicamento;
use App\Models\Solicitude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaMedicaController extends ApiController
{  
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Consultas_medica::with('solicitude','medico','examenes','medicamentos')->get();
        return $this->showAll($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'solicitude_id' => 'required|exists:solicitudes,id',
            'medico_id' => 'required',
            'fecha_asignada' => 'required',
            'fecha_asignada_fin' => 'required',
        ]);

        DB::beginTransaction();

        $data = $request->all();
        $consultas_medica = Consultas_medica::create($data);

        $solicitud = Solicitude::where('id',$request->solicitude_id)->first();

        if(!is_null($solicitud)){
            $solicitud->estado = 'P';
            $solicitud->save();
        }

        DB::commit();

        return $this->showOne($consultas_medica,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\consultas_medica  $consultas_medica
     * @return \Illuminate\Http\Response
     */
    public function show(Consultas_medica $consultas_medica)
    {
        return $this->showOne($consultas_medica);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\consultas_medica  $consultas_medica
     * @return \Illuminate\Http\Response
     */
    public function showByUser(Request $request)
    {
        $user = $request->user();
        $data = Consultas_medica::where('medico_id',$user->medico_id)->with('solicitude','medicamentos','examenes')->get();
        return $this->showAll($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\consultas_medica  $consultas_medica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultas_medica $consultas_medica)
    {
        $request->validate([
            'medico_id' => 'required',
            'fecha_asignada' => 'required',
            'fecha_asignada_fin' => 'required',
        ]);

        $consultas_medica->medico_id = $request->medico_id;
        $consultas_medica->fecha_asignada = $request->fecha_asignada;
        $consultas_medica->fecha_asignada_fin = $request->fecha_asignada_fin;

        if (!$consultas_medica->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $consultas_medica->save();

        return $this->showOne($consultas_medica,201);
    }

    //terminar consulta medica
    public function finish(Request $request, $id)
    {
        $request->validate([
            'diagnostico' => 'required'
        ]);

        DB::beginTransaction();

        $consultas_medica = Consultas_medica::find($id);

        $consultas_medica->diagnostico = $request->diagnostico;
        $consultas_medica->observaciones = $request->observaciones;

        //actualizar el estado de la solicitud
        $solicitud = Solicitude::where('id',$consultas_medica->solicitude_id)->first();

        if(!is_null($solicitud)){
            $solicitud->estado = 'F';
            $solicitud->save();   
        }


        //si examenes es mayor a 0
        $consultas_medica->examenes()->delete();
        if(count($request->examenes) > 0){
            foreach ($request->examenes as $examen) {
                Consultas_medicas_examene::create([
                    'consultas_medica_id' => $consultas_medica->id,
                    'examene_id'=>$examen['id'],
                    'precio'=>$examen['precio'],
                    'indicaciones'=>$examen['indicaciones']
                ]);
            }
        }

        //si medicinas es mayor a 0
        $consultas_medica->medicamentos()->delete();
        if(count($request->medicamentos) > 0){
            foreach ($request->medicamentos as $medicamento) {
                Consultas_medicas_medicamento::create([
                    'consultas_medica_id' => $consultas_medica->id,
                    'medicamento_id' => $medicamento['id'],
                    'cantidad' => $medicamento['cantidad'],
                    'tiempo_aplicacion'=>$medicamento['tiempo_aplicacion'],
                    'indicaciones'=>$medicamento['indicaciones'],
                    'precio'=>$medicamento['precio']
                ]);
            }
        }

        $consultas_medica->save();

        DB::commit();

        return $this->showOne($consultas_medica,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\consultas_medica  $consultas_medica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consultas_medica $consultas_medica)
    {
        $consultas_medica->delete();
        return $this->showOne($consultas_medica);
    }
}
