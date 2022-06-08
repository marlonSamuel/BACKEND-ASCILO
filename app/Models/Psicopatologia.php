<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psicopatologia extends Model
{
    use HasFactory;

    protected $table = 'psicopatologias';

    protected $fillable = [
      "nombre",'descripcion'
    ];
}
