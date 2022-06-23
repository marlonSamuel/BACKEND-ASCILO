<?php

namespace App\Http\Controllers\Api;

use App\Exports\ExamenesSheetExport;
use App\Exports\IngresosGastosSheetExport;
use App\Exports\MedicamentosSheetExport;
use App\Exports\PacienteExport;
use App\Exports\PagosFundacionSheetExport;
use App\Exports\PagosSheetExport;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Pacientes_medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

    public function pacientes(Request $request)
    {
    	$data = DB::table('pacientes as p')
                    ->join('psicopatologias as ps', 'p.psicopatologia_id', '=', 'ps.id')
                    ->select('p.id','p.cui as CUI',DB::raw("CONCAT(p.primer_nombre,' ',p.segundo_nombre,' ',p.tercer_nombre,' ',p.primer_apellido,' ',p.segundo_apellido)  AS Nombre"),'p.genero','p.fecha_nacimiento','p.direccion','cui_responsable as DPI responsable',DB::raw("CONCAT(p.primer_nombre_responsable,' ',p.segundo_nombre_responsable,' ',p.tercer_nombre_responsable,' ',p.primer_apellido_responsable,' ',p.segundo_apellido_responsable)  AS Responsable"),'p.parentesco','p.celular as celular contacto','p.telefono as telefono contacto','p.email as correo responsable','p.direccion_responsable as direccion responsable','ps.nombre as psicopatologia','p.fecha_ingreso as fecha de ingreso','p.razon as razon por internarlo','p.alergias')
                    ->get();

         foreach ($data as $key => $value) {
         	if($value->genero == 'M'){
         		$value->genero = 'Masculino';
         	}else{
         		$value->genero = 'Femenino';
         	}

         	switch ($value->parentesco) {
         		case 'H':
         			$value->parentesco = 'Hijo(a)';
         			break;
         		case 'P':
         			$value->parentesco = 'Primo(a)';
         			break;
         		case 'S':
         			$value->parentesco = 'Sobrino(a)';
         			break;
         		case 'A':
         			$value->parentesco = 'Amigo(a)';
         			break;
         		case 'C':
         			$value->parentesco = 'Conyuge';
         			break;
         	}

         	$medicamentos = Pacientes_medicamento::where('paciente_id',$value->id)->with('medicamento')->get();
         	$value->medicamentos = '';
         	foreach ($medicamentos as $m) {
         		$value->medicamentos = $value->medicamentos.', '.$m->medicamento->nombre;
         	}
         }

        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
            $data = $data->whereBetween('fecha de ingreso', [$request->from, $request->to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);
        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        return Excel::download(new PacienteExport('pacientes',$columns,$data,$header_info), 'transacciones.xlsx');

        return $this->showQuery($columns);
    }

    public function cobros(Request $request)
    {
    	$data = DB::table('pacientes as p')
                    ->join('pagos as pa', 'p.id', '=', 'pa.paciente_id')
                    ->join('anios as a','pa.anio_id','a.id')
                    ->join('meses as m','pa.mese_id','m.id')
                    ->select('pa.code as codigo','p.cui as CUI',DB::raw("CONCAT(p.primer_nombre,' ',p.segundo_nombre,' ',p.tercer_nombre,' ',p.primer_apellido,' ',p.segundo_apellido)  AS Nombre"),'cui_responsable as DPI responsable',DB::raw("CONCAT(p.primer_nombre_responsable,' ',p.segundo_nombre_responsable,' ',p.tercer_nombre_responsable,' ',p.primer_apellido_responsable,' ',p.segundo_apellido_responsable)  AS Responsable"),'a.anio','m.mes','pa.monto','pa.estado','pa.created_at as fecha')
                    ->get();



        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
        	$from = date($request->from). ' 00:00:00';
            $to = date($request->to). ' 23:59:59';
            $data = $data->whereBetween('fecha', [$from, $to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);

        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $data_pagados = $data->where('estado','P')->values();
        $data_anulados = $data->where('estado','A')->values();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        $dataReport = [
            'pagos completos' => [$columns, $data_pagados, $header_info],
            'pagos anulados'=>[$columns,$data_anulados, $header_info]
        ];

        return Excel::download(new PagosSheetExport($dataReport), 'clientes.xlsx');

        return $this->showQuery($data);
    }

    public function pagosFundacion(Request $request)
    {
    	$data = DB::table('costo_fundaciones as p')
                    ->join('consultas_medicas as c', 'p.consultas_medica_id', '=', 'c.id')
                    ->join('solicitudes as s','c.solicitude_id','s.id')
                    ->join('pacientes as pa','s.paciente_id','pa.id')
                    ->select('p.id as codigo','p.created_at as fecha de pago','c.fecha_asignada as Fecha de consulta','p.consulta as Costo consulta','p.medicamentos as Costo medicamentoes','p.examenes as costo_examenes','pa.cui as CUI',DB::raw("CONCAT(pa.primer_nombre,' ',pa.segundo_nombre,' ',pa.tercer_nombre,' ',pa.primer_apellido,' ',pa.segundo_apellido)  AS Paciente"),'p.pagado')
                    ->get();

        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
        	$from = date($request->from). ' 00:00:00';
            $to = date($request->to). ' 23:59:59';
            $data = $data->whereBetween('fecha de pago', [$from, $to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);

        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $data_pagados = $data->where('pagado',1)->values();
        $data_pendientes = $data->where('pagado',0)->values();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        $dataReport = [
            'pagos saldados' => [$columns, $data_pagados, $header_info],
            'pagos pendientes'=>[$columns,$data_pendientes, $header_info]
        ];

        return Excel::download(new PagosFundacionSheetExport($dataReport), 'clientes.xlsx');

        return $this->showQuery($data);
    }

    public function ingresosGastos(Request $request)
    {
    	$data = DB::table('ingresos_gastos as ig')
                    ->join('conceptos as c', 'ig.concepto_id', '=', 'c.id')
                    ->select('c.nombre as concepto','ig.monto','ig.observaciones as Descripcion','ig.created_at as fecha','ig.tipo')
                    ->get();

        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
        	$from = date($request->from). ' 00:00:00';
            $to = date($request->to). ' 23:59:59';
            $data = $data->whereBetween('fecha', [$from, $to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);

        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $data_gastos = $data->where('tipo','E')->values();
        $data_ingresos = $data->where('tipo','I')->values();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        $dataReport = [
            'Ingresos' => [$columns, $data_ingresos, $header_info],
            'Gastos'=>[$columns,$data_gastos, $header_info]
        ];

        return Excel::download(new IngresosGastosSheetExport($dataReport), 'ingresos-egresos.xlsx');

        return $this->showQuery($data);
    }

    public function examenes(Request $request)
    {
    	$data = DB::table('consultas_medicas_examenes as e')
    				->join('examenes as ex','e.examene_id','ex.id')
                    ->join('consultas_medicas as c', 'e.consultas_medica_id', '=', 'c.id')
                    ->join('solicitudes as s','c.solicitude_id','s.id')
                    ->join('pacientes as pa','s.paciente_id','pa.id')
                    ->select('ex.nombre','e.precio','e.indicaciones','c.fecha_asignada as Fecha de consulta','pa.cui as CUI',DB::raw("CONCAT(pa.primer_nombre,' ',pa.segundo_nombre,' ',pa.tercer_nombre,' ',pa.primer_apellido,' ',pa.segundo_apellido)  AS Paciente"),'e.realizado')
                    ->get();

        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
        	$from = date($request->from). ' 00:00:00';
            $to = date($request->to). ' 23:59:59';
            $data = $data->whereBetween('Fecha de consulta', [$from, $to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);

        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $data_realizados= $data->where('realizado',1)->values();
        $data_pendientes = $data->where('realizado',0)->values();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        $dataReport = [
            'examenes realizados' => [$columns, $data_realizados, $header_info],
            'examenes pendientes'=>[$columns,$data_pendientes, $header_info]
        ];

        return Excel::download(new ExamenesSheetExport($dataReport), 'clientes.xlsx');

        return $this->showQuery($data);
    }

    public function medicamentos(Request $request)
    {
    	$data = DB::table('consultas_medicas_medicamentos as m')
    				->join('medicamentos as me','m.medicamento_id','me.id')
                    ->join('consultas_medicas as c', 'm.consultas_medica_id', '=', 'c.id')
                    ->join('solicitudes as s','c.solicitude_id','s.id')
                    ->join('pacientes as pa','s.paciente_id','pa.id')
                    ->select('me.nombre','m.precio','m.cantidad','m.tiempo_aplicacion as Tiempo de aplicacion (dias)','m.indicaciones','c.fecha_asignada as Fecha de consulta','pa.cui as CUI',DB::raw("CONCAT(pa.primer_nombre,' ',pa.segundo_nombre,' ',pa.tercer_nombre,' ',pa.primer_apellido,' ',pa.segundo_apellido)  AS Paciente"),'m.entregado')
                    ->get();

        //filtrar por fechas
        if(!empty($request->from) && !empty($request->to)){
        	$from = date($request->from). ' 00:00:00';
            $to = date($request->to). ' 23:59:59';
            $data = $data->whereBetween('Fecha de consulta', [$from, $to]);
        }

        if(count($data) == 0) return $this->errorResponse("Data vacía, pruebe con otro rango de fechas",422);

        $columns = collect($data[0])->keys();
        $columns = $columns->toArray();

        $data_entregados= $data->where('entregado',1)->values();
        $data_pendientes = $data->where('entregado',0)->values();

        $header_info = [
            'from' => $request->from,
            'to' => $request->to
        ];

        $dataReport = [
            'medicamentos entregados' => [$columns, $data_entregados, $header_info],
            'medicamentos pendientes'=>[$columns,$data_pendientes, $header_info]
        ];

        return Excel::download(new MedicamentosSheetExport($dataReport), 'clientes.xlsx');

        
    }
}
