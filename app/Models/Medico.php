<?php

namespace App\Models;

use App\Models\Especialidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';

    protected $fillable = [
      "cui",'primer_nombre','segundo_nombre','tercer_nombre','primer_apellido','segundo_apellido','telefono','fecha_nacimiento','direccion','email','especialidade_id'
    ];

    public function especialidade()
    {
    	return $this->belongsTo(Especialidade::class);
    }
}
