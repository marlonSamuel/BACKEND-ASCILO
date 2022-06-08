<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermero extends Model
{
    use HasFactory;

 	protected $table = 'enfermeros';

    protected $fillable = [
      "cui",'primer_nombre','segundo_nombre','primer_apellido','segundo_apellido','telefono','fecha_nacimiento','direccion'
    ];
}
