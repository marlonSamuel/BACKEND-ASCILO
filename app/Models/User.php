<?php

namespace App\Models;

use App\Models\Client;
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
    protected $fillable = [
      "id",'username','email','role_id'
    ];


    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
