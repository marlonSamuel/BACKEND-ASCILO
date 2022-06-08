<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with('model_has_role.role')->orderByDesc('id')->get();
        return $this->showAll($data,200,true);
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
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'dpi' => 'required|unique:users',
            'first_name' => 'required|max:80|regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/',
            'last_name' => 'max:80|nullable|regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/',
            'password' => 'required|string|confirmed',
            'role_id' => 'required'
        ]);

        $request->id = null;
        DB::beginTransaction();
            $data = $request->all();
            $data['is_admin'] = true;
            $data['can_login_web'] = true;

            $data['password']=bcrypt($request->password);

            $user = User::create($data);

            ModelHasRole::create([
                'role_id'=> $request->role_id,
                'model_id'=> $user->id,
                'model_type'=> 'App\User'
            ]);

        DB::commit();

        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::where('id',$user->id)->with('client','merchant')->first();
        if(is_null($user)) $this->errorResponse('no existe usuario',421);

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
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'cellphone' => 'required',
            'dpi' => 'required',
            'first_name' => 'required|max:80|regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/',
            'last_name' => 'max:80|nullable|regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'
        ]);

        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->second_name = $request->second_name;
        $user->third_name = $request->third_name;
        $user->last_name = $request->last_name;
        $user->second_last_name = $request->second_last_name;
        $user->married_last_name = $request->married_last_name;
        $user->email = $request->email;
        $user->dpi = $request->dpi;

        DB::beginTransaction();
        $model_has_role = ModelHasRole::where('model_id',$user->id)->first();

        if(!is_null($model_has_role) && $request->has('role_id')){
            DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->update([
                    'role_id' => $request->role_id
                ]);
        }else{
            if (!$user->isDirty()) {
                return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
            }
        }
        $user->save();
        DB::commit();
        return $this->showOne($user);
    }

    //actualizar contraseña
    public function updatePassword(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'password' => "min:6|required_with:password_confirm|same:password_confirm",
            'password_confirm' => "required|min:6"
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password_confirm);
        $user->save();

        return $this->showOne($user,201);
    }

        //función para actulaizar estado
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'id'=>'required',
            'step_register'=>'required'
        ]);

        $user = User::find($id);
        $user->step_register = $request->step_register;

        if (!$user->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();

        return $this->showONe($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!$user->is_admin) return $this->errorResponse('no se puede eliminar usuario',422);
        $user->delete();
        return $this->showOne($user);
    }
}
