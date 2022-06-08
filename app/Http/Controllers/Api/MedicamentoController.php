<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends ApiController
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
        $data = Medicamento::all();
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
            'descripcion' => 'required',
            'costo' => 'required',
            'costo_con_descuento'=>'required'
        ]);

        $data = $request->all();
        $medicamento = Medicamento::create($data);

        return $this->showOne($medicamento,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medicamento  $Medicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Medicamento $medicamento)
    {
        return $this->showOne($medicamento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicamento  $Medicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicamento $medicamento)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'costo' => 'required',
            'costo_con_descuento'=>'required'
        ]);

        $medicamento->nombre = $request->nombre;
        $medicamento->descripcion = $request->descripcion;
        $medicamento->costo = $request->costo;
        $medicamento->costo_con_descuento = $request->costo_con_descuento;

        if (!$medicamento->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $medicamento->save();

        return $this->showOne($medicamento,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicamento  $Medicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicamento $medicamento)
    {
        $medicamento->delete();
        return $this->showOne($medicamento);
    }
}
