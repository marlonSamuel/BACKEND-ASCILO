<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ascilo extends Model
{
    use HasFactory;

    protected $table = 'ascilos';

    protected $fillable = [
    	'pago_mensual','consulta'
    ];
}
