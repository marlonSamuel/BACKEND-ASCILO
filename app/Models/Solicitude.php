<?php

namespace App\Models;

use App\Models\Consultas_medica;
use App\Models\Enfermero;
use App\Models\Especialidade;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitude extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
      "paciente_id",'enfermero_id','especialidade_id','fecha_visita','estado','motivo'
    ];

    public function paciente(){
    	return $this->belongsTo(Paciente::class);
    }

    public function enfermero(){
    	return $this->belongsTo(Enfermero::class);
    }

    public function especialidade(){
    	return $this->belongsTo(Especialidade::class);
    }

     public function consulta()
    {
        return $this->hasOne(Consultas_medica::class);
    }
}
