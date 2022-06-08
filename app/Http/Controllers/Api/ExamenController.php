<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Examene;
use Illuminate\Http\Request;

class ExamenController extends ApiController
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
        $data = Examene::all();
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
        $examene = Examene::create($data);

        return $this->showOne($examene,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\examene  $examene
     * @return \Illuminate\Http\Response
     */
    public function show(Examene $examene)
    {
        return $this->showOne($examene);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\examene  $examene
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Examene $examene)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'costo' => 'required',
            'costo_con_descuento'=>'required'
        ]);

        $examene->nombre = $request->nombre;
        $examene->descripcion = $request->descripcion;
        $examene->costo = $request->costo;
        $examene->costo_con_descuento = $request->costo_con_descuento;

        if (!$examene->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $examene->save();

        return $this->showOne($examene,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\examene  $examene
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examene $examene)
    {
        $examene->delete();
        return $this->showOne($examene);
    }
}
