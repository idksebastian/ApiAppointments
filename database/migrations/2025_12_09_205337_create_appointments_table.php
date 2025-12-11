<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //CREAMOS LA TABLA DE APPOINTMENTS Y LOS CAMPOS
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_paciente');
            $table->string('nombre_doctor');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->text('motivo_cita')->nullable();
            $table->enum('estado_cita', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->text('consultorio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //ELIMINAMOS LA TABLA DE APPOINTMENTS
        Schema::dropIfExists('appointments');
    }
};
