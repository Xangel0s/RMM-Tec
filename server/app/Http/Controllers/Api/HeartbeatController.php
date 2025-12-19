<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertIncident;
use App\Models\AlertRule;
use App\Models\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        if (! empty($validated['hardware_summary'])) {
            $hw = $validated['hardware_summary'];
            $metrics = [
                'cpu_usage' => (float) ($hw['cpu_usage_percent'] ?? 0),
                'ram_usage' => (float) ($hw['ram_used_percent'] ?? 0),
                'ram_total' => (float) ($hw['ram_total_gb'] ?? 0),
                'disk_usage' => (float) ($hw['disk_used_percent'] ?? 0),
            ];

            $endpoint->metrics()->create($metrics);

            // Verificar Alertas
            $this->checkAlerts($endpoint, $metrics);
        }

        return response()->json(['status' => 'ok', 'endpoint_id' => $endpoint->id]);
    }

    private function checkAlerts(Endpoint $endpoint, array $metrics)
    {
        // Obtener reglas globales (endpoint_id null) o específicas de este endpoint
        $rules = AlertRule::where('is_active', true)
            ->where(function ($query) use ($endpoint) {
                $query->whereNull('endpoint_id')
                    ->orWhere('endpoint_id', $endpoint->id);
            })
            ->get();

        foreach ($rules as $rule) {
            $currentValue = 0;
            $isTriggered = false;

            // Determinar valor actual según métrica
            switch ($rule->metric) {
                case 'cpu':
                    $currentValue = $metrics['cpu_usage'];
                    break;
                case 'ram':
                    $currentValue = $metrics['ram_usage'];
                    break;
                case 'disk':
                    $currentValue = $metrics['disk_usage'];
                    break;
                default:
                    continue 2;
            }

            // Evaluar condición
            if ($currentValue >= $rule->threshold) {
                $isTriggered = true;
            }

            // Gestionar Incidentes
            $existingIncident = AlertIncident::where('alert_rule_id', $rule->id)
                ->where('endpoint_id', $endpoint->id)
                ->whereNull('resolved_at')
                ->first();

            if ($isTriggered) {
                if (! $existingIncident) {
                    // Crear nuevo incidente
                    AlertIncident::create([
                        'alert_rule_id' => $rule->id,
                        'endpoint_id' => $endpoint->id,
                        'triggered_at' => now(),
                        'message' => "High {$rule->metric} usage detected: {$currentValue}% (Threshold: {$rule->threshold}%)",
                    ]);

                    // TODO: Ejecutar Acción (Email/Webhook)
                    Log::channel('daily')->warning("ALERT TRIGGERED: {$endpoint->hostname} - {$rule->metric} is at {$currentValue}%");
                }
            } else {
                if ($existingIncident) {
                    // Resolver incidente
                    $existingIncident->update([
                        'resolved_at' => now(),
                        'message' => $existingIncident->message." | Resolved. Current: {$currentValue}%",
                    ]);
                }
            }
        }
    }
}
