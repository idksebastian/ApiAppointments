<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable =[
        'nombre_paciente',
        'nombre_doctor',
        'fecha_cita',
        'hora_cita',
        'motivo_cita',
        'estado_cita',
    ];
}
