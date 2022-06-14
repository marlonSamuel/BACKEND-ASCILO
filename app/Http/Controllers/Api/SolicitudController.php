<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Solicitude;
use Illuminate\Http\Request;

class SolicitudController extends ApiController
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
        $data = Solicitude::with('paciente','enfermero','especialidade','consulta.medico')->orderBy('fecha_visita')->get();
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
            'enfermero_id' => 'required',
            'especialidade_id' => 'required',
            'paciente_id' => 'required',
            'motivo' => 'required',
            'fecha_visita' => 'required'
        ]);

        $data = $request->all();
        $solicitude = Solicitude::create($data);

        return $this->showOne($solicitude,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\solicitude  $solicitude
     * @return \Illuminate\Http\Response
     */
    public function show(Solicitude $solicitude)
    {
        return $this->showOne($solicitude);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\solicitude  $solicitude
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Solicitude $solicitude)
    {
        $request->validate([
            'enfermero_id' => 'required',
            'especialidade_id' => 'required',
            'paciente_id' => 'required',
            'motivo' => 'required',
            'fecha_visita' => 'required'
        ]);

        $solicitude->enfermero_id = $request->enfermero_id;
        $solicitude->especialidade_id = $request->especialidade_id;
        $solicitude->paciente_id = $request->paciente_id;
        $solicitude->motivo = $request->motivo;
        $solicitude->fecha_visita = $request->fecha_visita;

        if (!$solicitude->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $solicitude->save();

        return $this->showOne($solicitude,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\solicitude  $solicitude
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitude $solicitude)
    {
        $solicitude->delete();
        return $this->showOne($solicitude);
    }
}
