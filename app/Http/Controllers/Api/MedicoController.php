<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MedicoController extends ApiController
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
        $data = Medico::with('especialidade')->get();
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
            'cui'=>'required|unique:medicos',
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'telefono' => 'required',
            'email' => 'required|string|email|unique:medicos',
            'especialidade_id' => 'required'
        ]);

        DB::beginTransaction();

        $data = $request->all();
        $medico = medico::create($data);

        $role = Role::where('nombre','medico')->first();

        
        if(!is_null($role)){
            User::create([
                "role_id" => $role->id,
                "nombres" => $medico->primer_nombre,
                "apellidos" => $medico->primer_apellido,
                "email"=> $medico->email,
                "username"=>$medico->cui,
                "password" => Hash::make($medico->cui),
                "medico_id" => $medico->id
            ]); 
        }
        

        DB::commit();

        return $this->showOne($medico,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function show(Medico $medico)
    {
        return $this->showOne($medico);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'cui'=>'required|string|unique:medicos,cui,' . $medico->id,
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'direccion' => 'required',
            'fecha_nacimiento' => 'required',
            'telefono' => 'required',
            'email' => 'required|string|unique:medicos,email,' . $medico->id,
            'especialidade_id' => 'required'
        ]);

        DB::beginTransaction();

        $medico->primer_nombre = $request->primer_nombre;
        $medico->primer_apellido = $request->primer_apellido;
        $medico->segundo_nombre = $request->segundo_nombre;
        $medico->segundo_apellido = $request->segundo_apellido;
        $medico->direccion = $request->direccion;
        $medico->fecha_nacimiento = $request->fecha_nacimiento;
        $medico->telefono = $request->telefono;
        $medico->cui = $request->cui;
        $medico->tercer_nombre = $request->tercer_nombre;
        $medico->email = $request->email;
        $medico->especialidade_id = $request->especialidade_id;

        if (!$medico->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $usuario = User::where('medico_id',$medico->id)->first();
        if(!is_null($usuario))
        {
            $usuario->email = $request->email;
            $usuario->username = $request->cui;
            $usuario->nombres = $request->primer_nombre;
            $usuario->apellidos = $request->primer_apellido;
            $usuario->save();
        }

        $medico->save();

        DB::commit();

        return $this->showOne($medico,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\medico  $medico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medico $medico)
    {
        $medico->delete();
        return $this->showOne($medico);
    }
}
