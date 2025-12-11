<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Appointment::all();
        
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    if (!$request->nombre_paciente || !$request->nombre_doctor || !$request->fecha_cita || !$request->hora_cita || !$request->estado_cita)
        {
        return response()->json([
            'error' => 'Todos los campos deben estar llenos'
        ], 400);
    }
    if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $request->hora_cita)) {
        return response()->json([
            'error' => 'La hora no es válida. Debe tener el formato'
        ], 400);
    }
    if($request->estado_cita !== 'pendiente' && $request->estado_cita !== 'completada' && $request->estado_cita !== 'cancelada'){
        return response()->json([
            'error' => 'El estado de la cita debe ser pendiente, completada o cancelada'
        ], 400);
    }
    $appointment = Appointment::create([
        'nombre_paciente' => $request->nombre_paciente,
        'nombre_doctor'   => $request->nombre_doctor,
        'fecha_cita'      => $request->fecha_cita,
        'hora_cita'       => $request->hora_cita,
        'motivo_cita'     => $request->motivo_cita,
        'estado_cita'     => $request->estado_cita,
    ]);
    
    if (!$appointment) {
        return response()->json([
            'error' => 'La cita no se pudo crear'
        ], 500);
    }
    return response()->json([
        'bien' => 'Cita creada correctamente',
        'data' => $appointment
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return $appointment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'nombre_paciente' => 'sometimes|required|string|max:255',
            'nombre_doctor' => 'sometimes|required|string|max:255',
            'fecha_cita' => 'sometimes|required|date',
            'hora_cita' => 'sometimes|required',
            'motivo_cita' => 'nullable|string',
            'estado_cita' => 'sometimes|required|in:pendiente,completada,cancelada',
        ]);

        $appointment->update($data);
        return $appointment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'Cita Eliminada']);
    }
}
