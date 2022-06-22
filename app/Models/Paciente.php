<?php

namespace App\Models;

use App\Models\Pacientes_medicamento;
use App\Models\Psicopatologia;
use App\Models\Solicitude;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
    	'cui',
        'psicopatologia_id',
        'primer_nombre',
        'segundo_nombre',
        'tercer_nombre',
        'primer_apellido',
        'segundo_apellido',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'cui_responsable',
        'primer_nombre_responsable',
        'segundo_nombre_responsable',
        'tercer_nombre_responsable',
        'primer_apellido_responsable',
        'segundo_apellido_responsable',
        'parentesco',
        'celular',
        'telefono',
        'email',
        'fecha_nacimiento_responsable',
        'razon',
        'alergias',
        'direccion_responsable'
    ];

    public function psicopatologia()
    {
    	return $this->belongsTo(Psicopatologia::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitude::class);
    }

    public function medicamentos()
    {
        return $this->hasMany(Pacientes_medicamento::class);
    }
}
