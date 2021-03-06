<?php

namespace App\Models;

use App\Models\Consultas_medica;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoFundacione extends Model
{
    use HasFactory;
    protected $table = 'costo_fundaciones';

    protected $fillable = [
    	'consultas_medica_id','consulta','medicamentos','examenes','total','pagado'
    ];


    public function consulta_medica()
    {
    	return $this->belongsTo(Consultas_medica::class,'consultas_medica_id');
    }
}
