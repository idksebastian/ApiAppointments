<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function index()
    {
        //MOSTRAMOS TODAS LAS CITAS
        return Appointment::all();
        
    }

    public function store(Request $request)
    {
        //VALIDAMOS QUE TODOS LOS CAMPOS ESTEN LLENOS
        if (!$request->nombre_paciente || !$request->nombre_doctor || !$request->fecha_cita || !$request->hora_cita || !$request->estado_cita || !$request->consultorio)
            {
            return response()->json([
                'error' => 'Todos los campos deben estar llenos'
            ], 400);
        }
        //VALIDAMOS QUE LA HORA SEA VALIDA
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $request->hora_cita)) {
            return response()->json([
                'error' => 'La hora no es válida'
            ], 400);
        }
        // VALIDAMOS QUE EL ESTADO DE LA CITA SEA VALIDO
        if($request->estado_cita !== 'pendiente' && $request->estado_cita !== 'completada' && $request->estado_cita !== 'cancelada'){
            return response()->json([
                'error' => 'El estado de la cita debe ser pendiente, completada o cancelada'
            ], 400);
        }
        //CREAMOS LA CITA
        $appointment = Appointment::create([
            'nombre_paciente' => $request->nombre_paciente,
            'nombre_doctor'   => $request->nombre_doctor,
            'fecha_cita'      => $request->fecha_cita,
            'hora_cita'       => $request->hora_cita,
            'motivo_cita'     => $request->motivo_cita,
            'estado_cita'     => $request->estado_cita,
            'consultorio'     => $request->consultorio,
        ]);
        //SI LA CITA NO SE CREO TIRA UN ERROR
        if (!$appointment) {
            return response()->json([
                'error' => 'La cita no se pudo crear'
            ], 500);
        }
        //RETORNAMOS LA CITA CREADA
        return response()->json([
        'bien' => 'Cita creada correctamente',
        'data' => $appointment
        ], 201);
}

    public function show(Appointment $appointment)
    {
        //MOSTRAMOS LA CITA
        return $appointment;
    }

    public function update(Request $request, Appointment $appointment)
    {
        //VALIDAMOS LOS CAMPOS A ACTUALIZAR
        $data = $request->validate([
            'nombre_paciente' => 'sometimes|required|string|max:255',
            'nombre_doctor' => 'sometimes|required|string|max:255',
            'fecha_cita' => 'sometimes|required|date',
            'hora_cita' => 'sometimes|required',
            'motivo_cita' => 'nullable|string',
            'estado_cita' => 'sometimes|required|in:pendiente,completada,cancelada',
            'consultorio' => 'nullable|string'
        ]);
        //ENVIAMOS LOS DATOS ACTUALIZADOS
        $appointment->update($data);
        return $appointment;
    }


    public function destroy(Appointment $appointment)
    {
        //ELIMINAMOS LA CITA
        $appointment->delete();
        return response()->json(['mensaje' => 'Cita Eliminada']);
    }
}
