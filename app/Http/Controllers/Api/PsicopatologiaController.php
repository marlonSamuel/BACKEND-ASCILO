<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Psicopatologia;
use Illuminate\Http\Request;

class PsicopatologiaController extends ApiController
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
        $data = Psicopatologia::all();
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
        $psicopatologia = Psicopatologia::create($data);

        return $this->showOne($psicopatologia,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Psicopatologia  $psicopatologia
     * @return \Illuminate\Http\Response
     */
    public function show(Psicopatologia $psicopatologia)
    {
        return $this->showOne($psicopatologia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Psicopatologia  $psicopatologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Psicopatologia $psicopatologia)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $psicopatologia->nombre = $request->nombre;
        $psicopatologia->descripcion = $request->descripcion;

        if (!$psicopatologia->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $psicopatologia->save();

        return $this->showOne($psicopatologia,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Psicopatologia  $psicopatologia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Psicopatologia $psicopatologia)
    {
        $psicopatologia->delete();
        return $this->showOne($psicopatologia);
    }
}
