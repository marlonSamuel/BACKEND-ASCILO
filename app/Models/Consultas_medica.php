<?php

namespace App\Models;

use App\Models\Consultas_medicas_examene;
use App\Models\Consultas_medicas_medicamento;
use App\Models\Medico;
use App\Models\Solicitude;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultas_medica extends Model
{
    use HasFactory;

    protected $table = 'consultas_medicas';

    protected $fillable = [
    	'solicitude_id','medico_id','fecha_asignada','fecha_asignada_fin','diagnostico','observaciones'
    ];

    public function solicitude(){
    	return $this->belongsTo(Solicitude::class);
    }

    public function medico(){
    	return $this->belongsTo(Medico::class);
    }

    public function medicamentos()
    {
        return $this->hasMany(Consultas_medicas_medicamento::class);
    }

    public function examenes()
    {
        return $this->hasMany(Consultas_medicas_examene::class);
    }
}
