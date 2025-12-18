<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Endpoint;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostname' => 'required|string',
            'api_token' => 'required|string',
            'local_ip' => 'nullable|string',
            'public_ip' => 'nullable|string',
            'os_info' => 'nullable|string',
            'hardware_summary' => 'nullable|array',
            'rustdesk_id' => 'nullable|string',
        ]);

        $endpoint = Endpoint::updateOrCreate(
            ['api_token' => $validated['api_token']],
            [
                'hostname' => $validated['hostname'],
                'local_ip' => $validated['local_ip'] ?? null,
                'public_ip' => $validated['public_ip'] ?? null,
                'os_info' => $validated['os_info'] ?? null,
                'hardware_summary' => $validated['hardware_summary'] ?? null,
                'rustdesk_id' => $validated['rustdesk_id'] ?? null,
                'last_seen_at' => now(),
                'status' => 'online',
            ]
        );

        // Guardar métricas históricas
        if (!empty($validated['hardware_summary'])) {
            $hw = $validated['hardware_summary'];
            $endpoint->metrics()->create([
                'cpu_usage' => (float) ($hw['cpu_usage_percent'] ?? 0),
                'ram_usage' => (float) ($hw['ram_used_percent'] ?? 0),
                'ram_total' => (float) ($hw['ram_total_gb'] ?? 0),
                'disk_usage' => (float) ($hw['disk_used_percent'] ?? 0),
            ]);
        }

        return response()->json(['status' => 'ok', 'endpoint_id' => $endpoint->id]);
    }
}
