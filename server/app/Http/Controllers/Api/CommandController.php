<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Command;
use App\Models\Endpoint;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    // Obtener comandos pendientes para el agente
    public function pending(Request $request)
    {
        // 1. Validar el token del agente (simple auth por ahora)
        $apiToken = $request->query('api_token');
        if (!$apiToken) {
            return response()->json(['error' => 'API Token required'], 401);
        }

        $endpoint = Endpoint::where('api_token', $apiToken)->first();
        if (!$endpoint) {
            return response()->json(['error' => 'Invalid API Token'], 401);
        }

        // 2. Buscar comandos pendientes
        $commands = Command::where('endpoint_id', $endpoint->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // 3. Devolver lista
        return response()->json($commands);
    }

    // Reportar resultado de un comando
    public function result(Request $request, $id)
    {
        $command = Command::find($id);
        if (!$command) {
            return response()->json(['error' => 'Command not found'], 404);
        }

        $command->update([
            'status' => $request->input('status', 'completed'), // completed or failed
            'output' => $request->input('output'),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
