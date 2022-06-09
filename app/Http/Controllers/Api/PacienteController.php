<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends ApiController
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
        $data = Paciente::with('psicopatologia')->get();
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
            'cui'=>'required|unique:pacientes',
            'psicopatologia_id'=>'required',
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'celular' => 'required',
            'email' => 'required|string|email|unique:pacientes',
            'cui_responsable'=>'required|unique:pacientes',
            'primer_nombre_responsable' => 'required',
            'primer_apellido_responsable' => 'required',
            'parentesco'=>'required',
            'razon'=>'required',
            'fecha_nacimiento_responsable',
            'direccion_responsable'=>'required',
            'genero'=>'required'
        ]);

        $data = $request->all();
        $paciente = Paciente::create($data);

        return $this->showOne($paciente,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        return $this->showOne($paciente);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'cui'=>'required|string|unique:pacientes,cui,' . $paciente->id,
            'psicopatologia_id'=>'required',
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'celular' => 'required',
            'email' => 'required|string|unique:pacientes,email,' . $paciente->id,
            'cui_responsable'=>'required|string|unique:pacientes,cui_responsable,' . $paciente->id,
            'primer_nombre_responsable' => 'required',
            'primer_apellido_responsable' => 'required',
            'parentesco'=>'required',
            'razon'=>'required',
            'fecha_nacimiento_responsable',
            'direccion_responsable'=>'required',
            'genero'=>'required'
        ]);

        $paciente->cui = $request->cui;
        $paciente->psicopatologia_id = $request->psicopatologia_id;
        $paciente->primer_nombre = $request->primer_nombre;
        $paciente->primer_apellido = $request->primer_apellido;
        $paciente->tercer_nombre = $request->tercer_nombre;
        $paciente->segundo_nombre = $request->segundo_nombre;
        $paciente->segundo_apellido = $request->segundo_apellido;
        $paciente->direccion = $request->direccion;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->parentesco = $request->parentesco;
        $paciente->razon = $request->razon;
        $paciente->alergias = $request->alergias;
        $paciente->genero = $request->genero;

        //información del responsable
        $paciente->cui_responsable = $request->cui_responsable;
        $paciente->primer_nombre_responsable = $request->primer_nombre_responsable;
        $paciente->segundo_nombre_responsable = $request->segundo_nombre_responsable;
        $paciente->primer_apellido_responsable = $request->primer_apellido_responsable;
        $paciente->segundo_nombre_responsable = $request->segundo_nombre_responsable;
        $paciente->fecha_nacimiento_responsable = $request->fecha_nacimiento_responsable;
        $paciente->direccion_responsable = $request->direccion_responsable;
        $paciente->email = $request->email;
        $paciente->celular = $request->celular;
        $paciente->telefono = $request->telefono;
        

        if (!$paciente->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $paciente->save();

        return $this->showOne($paciente,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return $this->showOne($paciente);
    }
}
