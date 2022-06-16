<?php

namespace App\Models;

use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultas_medicas_medicamento extends Model
{
    use HasFactory;
    protected $table = 'consultas_medicas_medicamentos';

    protected $fillable = [
    	'consultas_medica_id','medicamento_id','cantidad','tiempo_aplicacion','indicaciones','precio','entregado'
    ];

    public function consultas_medica(){
    	return $this->belongsTo(Consultas_medica::class);
    }

    public function medicamento(){
    	return $this->belongsTo(Medicamento::class);
    }
}
