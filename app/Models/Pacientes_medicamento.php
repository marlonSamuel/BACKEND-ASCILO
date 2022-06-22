<?php

namespace App\Models;

use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes_medicamento extends Model
{
    use HasFactory;

    protected $table = 'pacientes_medicamentos';

    protected $fillable = [
      "paciente_id",'medicamento_id'
    ];

    public function paciente(){
    	return $this->belongsTo(Paciente::class);
    }

    public function medicamento(){
    	return $this->belongsTo(Medicamento::class);
    }
}
