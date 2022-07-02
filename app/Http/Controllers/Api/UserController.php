<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\ModelHasRole;
use App\Models\Paciente;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserController extends ApiController
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
        $data = User::with('role')->where('medico_id','0')->orderByDesc('id')->get();
        return $this->showAll($data);
    }

    //obtener roles
    public function roles()
    {
        $data = Role::where('nombre','!=','medico')->get();
        return $this->showAll($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $request->validate([
            'username' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'role_id' => 'required'
        ]);

        $user = new User([
            'nombres'     => $request->nombres,
            'apellidos'     => $request->apellidos,
            'username'     => $request->username,
            'email'    => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
        ]);

        $user->save();
        
        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
       return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'email' => 'required|string|unique:users,email,' . $user->id,
        ];

        $this->validate($request, $reglas);

        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->username = $request->username;

        if (!$user->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();
        return $this->showOne($user);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (Hash::check($request->new_password, $user->password)) {
            return $this->errorResponse('la contraseña actual no puede ser igual a la nueva contraseña',422);
        }

        if (Hash::check($request->old_password, $user->password)) { 
            $user->password = bcrypt($request->new_password);
            $user->save();
        } else {
            return $this->errorResponse('la contraseña anterior es incorrecta',422);
        }


        return $this->showOne($user,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user,201);
    }
}
