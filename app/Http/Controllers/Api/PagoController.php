<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Ascilo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends ApiController
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
        $data = Pago::with('anio','mes','paciente')->get();

        $groups = $data->groupBy('code'); 

        // we will use map to cumulate each group of rows into single row.
        // $group is a collection of rows that has the same opposition_id.
        $groupwithcount = $groups->map(function ($group) {
            return [
                'code'=> $group[0]->code,
                'total' => $group->sum('monto'),
                'anio' => $group[0]->anio,
                'estado' => $group[0]->estado,
                'paciente' => $group[0]->paciente,
                'meses'=> $group->groupBy('mes')->map(function($item){
                    return [
                        'id' => $item[0]->mes->id,
                        'mes' => $item[0]->mes->mes,
                        'no' => $item[0]->mes->no,
                        'monto' => $item[0]->monto
                    ];
                })->values()
            ];
        });

        return $this->showQuery($groupwithcount->values());
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
            'paciente_id' => 'required',
            'anio_id' => 'required'
        ]);

        if(count($request->meses) == 0) return $this->errorResponse("Seleccione al menos un mes a cancelar",422);

        DB::beginTransaction();
        $ascilo = Ascilo::first();
        if(!is_null($ascilo)){
            $id = DB::table('pagos')->latest('id')->first();
            $code = 'P-1';
            if(!is_null($id)){
                $plus = explode("-", $id->code)[1];
                $code = 'P-'.$plus+1;
            }
            foreach ($request->meses as $mes) {
                Pago::create([
                    'paciente_id' => $request->paciente_id,
                    'anio_id' => $request->anio_id,
                    'mese_id' => $mes['id'],
                    'monto' => $ascilo->pago_mensual,
                    'code' => $code
                ]);
            }
        }

        DB::commit();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

        //obtener configuracion
    public function ascilo()
    {
        $data = Ascilo::first();
        return $this->showOne($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    public function anular($code)
    {
        $updated = DB::table('pagos')
              ->where('code', $code)
              ->update(['estado' => 'A']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
        
    }
}
