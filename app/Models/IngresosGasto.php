<?php

namespace App\Models;

use App\Models\Concepto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresosGasto extends Model
{
    use HasFactory;

    protected $table = 'ingresos_gastos';

    protected $fillable = [
    	'concepto_id','monto','observaciones','tipo'
    ];

    public function concepto()
    {
    	return $this->belongsTo(Concepto::class);
    }

}
