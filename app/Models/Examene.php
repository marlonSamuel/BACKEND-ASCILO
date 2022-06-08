<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examene extends Model
{
    use HasFactory;

     protected $table = 'examenes';

    protected $fillable = [
      "nombre",'descripcion','costo','costo_con_descuento'
    ];
}
