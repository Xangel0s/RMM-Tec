<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertIncident;
use App\Models\Endpoint;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ExternalTicketController extends Controller
{
    // ... existing store method ...

    public function dashboard(Request $request)
    {
        // Public/Portal Dashboard Stats
        $totalEndpoints = Endpoint::count();
        $onlineEndpoints = Endpoint::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        $activeAlerts = AlertIncident::whereNull('resolved_at')
            ->with('endpoint:id,hostname')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'device' => $alert->endpoint->hostname ?? 'Unknown',
                    'message' => $alert->message,
                    'time' => $alert->triggered_at->diffForHumans(),
                    'severity' => 'high', // Simplified for now
                ];
            });

        return response()->json([
            'stats' => [
                'total_devices' => $totalEndpoints,
                'online_devices' => $onlineEndpoints,
                'offline_devices' => $totalEndpoints - $onlineEndpoints,
                'active_alerts' => AlertIncident::whereNull('resolved_at')->count(),
            ],
            'recent_alerts' => $activeAlerts,
            'system_status' => 'operational',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'priority' => 'required|in:low,medium,high,critical',
            'department' => 'required|string',
        ]);

        // Find or create user (simplified for external portal)
        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => explode('@', $validated['email'])[0], 'password' => bcrypt('random')]
        );

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'department' => $validated['department'],
            'status' => 'open',
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => 'success',
            'public_id' => $ticket->public_id,
            'message' => 'Ticket created successfully.',
        ], 201);
    }

    public function track($public_id)
    {
        $ticket = Ticket::where('public_id', $public_id)->firstOrFail();

        // Map internal status to client-facing status
        $clientStatus = match ($ticket->status) {
            'resolved' => 'En línea', // Assuming resolved means back online/fixed
            'in_progress' => 'Gestionado',
            'open' => 'Gestionado', // Open tickets are also "managed" by support
            default => 'Gestionado',
        };

        return response()->json([
            'id' => $ticket->public_id,
            'title' => $ticket->title,
            'status' => $clientStatus,
            'updated_at' => $ticket->updated_at->diffForHumans(),
        ]);
    }
}
