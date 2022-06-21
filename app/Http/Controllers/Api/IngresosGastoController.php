<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\IngresosGasto;
use Illuminate\Http\Request;

class IngresosGastoController extends ApiController
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
        $data = IngresosGasto::with('concepto')->get();
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
            'monto' => 'required',
            'concepto_id' => 'required',
            'tipo' => 'required',
            'observaciones'=>'required'
        ]);

        $data = $request->all();
        $ingresosGasto = IngresosGasto::create($data);

        return $this->showOne($ingresosGasto,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ingresosGasto  $ingresosGasto
     * @return \Illuminate\Http\Response
     */
    public function show(IngresosGasto $ingresosGasto)
    {
        return $this->showOne($ingresosGasto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ingresosGasto  $ingresosGasto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IngresosGasto $ingresosGasto)
    {
        $request->validate([
            'monto' => 'required',
            'concepto_id' => 'required',
            'tipo' => 'required',
            'observaciones'=>'required'
        ]);

        $ingresosGasto->monto = $request->monto;
        $ingresosGasto->concepto_id = $request->concepto_id;
        $ingresosGasto->tipo = $request->tipo;
        $ingresosGasto->observaciones = $request->observaciones;

        if (!$ingresosGasto->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $ingresosGasto->save();

        return $this->showOne($ingresosGasto,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ingresosGasto  $ingresosGasto
     * @return \Illuminate\Http\Response
     */
    public function destroy(IngresosGasto $ingresosGasto)
    {
        $ingresosGasto->delete();
        return $this->showOne($ingresosGasto);
    }
}