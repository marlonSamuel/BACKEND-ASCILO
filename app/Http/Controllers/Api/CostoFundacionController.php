<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Consultas_medica;
use App\Models\CostoFundacione;
use App\Models\Solicitude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostoFundacionController extends ApiController
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
        $data = CostoFundacione::with('consulta_medica.solicitude.paciente','consulta_medica.medicamentos.medicamento','consulta_medica.examenes.examene')->orderByDesc('pagado')->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CostoFundacione  $costoFundacione
     * @return \Illuminate\Http\Response
     */
    public function show(CostoFundacione $costoFundacione)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CostoFundacione  $costoFundacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $costoFundacion = CostoFundacione::find($id);
        if(is_null($costoFundacion)){
            return $this->errorResponse('registro no existe',404);
        }

        $costoFundacion->pagado = true;
        $costoFundacion->save();

        $consulta = Consultas_medica::find($costoFundacion->consultas_medica_id);
        $solicitude = Solicitude::find($consulta->solicitude_id);
        $solicitude->estado = 'C';
        $solicitude->save();

        DB::commit();
        return $this->showOne($consulta,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostoFundacione  $costoFundacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostoFundacione $costoFundacione)
    {
        //
    }
}
