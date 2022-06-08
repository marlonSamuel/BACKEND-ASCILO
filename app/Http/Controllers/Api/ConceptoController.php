<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Concepto;
use Illuminate\Http\Request;

class ConceptoController extends ApiController
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
        $data = Concepto::all();
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
        $concepto = Concepto::create($data);

        return $this->showOne($concepto,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function show(Concepto $concepto)
    {
        return $this->showOne($concepto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concepto $concepto)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $concepto->nombre = $request->nombre;
        $concepto->descripcion = $request->descripcion;

        if (!$concepto->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $concepto->save();

        return $this->showOne($concepto,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concepto $concepto)
    {
        $concepto->delete();
        return $this->showOne($concepto);
    }
}
