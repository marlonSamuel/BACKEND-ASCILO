<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Enfermero;
use Illuminate\Http\Request;

class EnfermeroController extends ApiController
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
        $data = Enfermero::all();
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
            'cui'=>'required',
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'telefono' => 'required'
        ]);

        $data = $request->all();
        $enfermero = Enfermero::create($data);

        return $this->showOne($enfermero,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\enfermero  $enfermero
     * @return \Illuminate\Http\Response
     */
    public function show(Enfermero $enfermero)
    {
        return $this->showOne($enfermero);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\enfermero  $enfermero
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enfermero $enfermero)
    {
        $request->validate([
            'cui'=>'required',
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'telefono' => 'required'
        ]);

        $enfermero->primer_nombre = $request->primer_nombre;
        $enfermero->primer_apellido = $request->primer_apellido;
        $enfermero->segundo_nombre = $request->segundo_nombre;
        $enfermero->segundo_apellido = $request->segundo_apellido;
        $enfermero->direccion = $request->direccion;
        $enfermero->fecha_nacimiento = $request->fecha_nacimiento;
        $enfermero->telefono = $request->telefono;
        $enfermero->cui = $request->cui;

        if (!$enfermero->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $enfermero->save();

        return $this->showOne($enfermero,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\enfermero  $enfermero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enfermero $enfermero)
    {
        $enfermero->delete();
        return $this->showOne($enfermero);
    }
}
