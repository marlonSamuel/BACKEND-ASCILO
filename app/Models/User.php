<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Medico;
use App\Models\Merchant;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\UserDevice;
use App\Models\UserQuestion;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $hidden = ['password'];
    protected $fillable = [
      "id",'nombres','apellidos','username','email','role_id','medico_id','password'
    ];

    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function medico()
    {
        return $this->hasOne(Medico::class);
    }
}
