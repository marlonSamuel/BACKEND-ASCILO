<?php

namespace App\Models;

use App\Models\Anio;
use App\Models\Mese;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pagos';

    protected $fillable = [
    	'paciente_id','anio_id','mese_id','monto','code','estado'
    ];

    public function anio()
    {
    	return $this->belongsTo(Anio::class);
    }

    public function mes()
    {
    	return $this->belongsTo(Mese::class,'mese_id');
    }

    public function paciente()
    {
    	return $this->belongsTo(Paciente::class);
    }
}
