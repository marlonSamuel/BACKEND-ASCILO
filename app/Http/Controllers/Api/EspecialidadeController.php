<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class EspecialidadeController extends ApiController
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
        $data = Especialidade::all();
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
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $data = $request->all();
        $especialidade = Especialidade::create($data);

        return $this->showOne($especialidade,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function show(Especialidade $especialidade)
    {
        return $this->showOne($especialidade);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\especialiade  $especialiade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $especialidade->nombre = $request->nombre;
        $especialidade->descripcion = $request->descripcion;

        if (!$especialidade->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $especialidade->save();

        return $this->showOne($especialidade,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\especialiade  $especialiade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Especialidade $especialidade)
    {
        $especialidade->delete();
        return $this->showOne($especialidade);
    }
}

