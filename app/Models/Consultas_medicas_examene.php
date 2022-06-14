<?php

namespace App\Models;

use App\Models\Consultas_medica;
use App\Models\Examene;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultas_medicas_examene extends Model
{
    use HasFactory;
    protected $table = 'consultas_medicas_examenes';

    protected $fillable = [
    	'consultas_medica_id','examene_id','precio','indicaciones'
    ];

    public function consultas_medica(){
    	return $this->belongsTo(Consultas_medica::class);
    }

    public function examene(){
    	return $this->belongsTo(Examene::class);
    }
}
