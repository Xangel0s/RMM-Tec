<?php

namespace Database\Seeders;

use App\Models\Endpoint;
use App\Models\Metric;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class MonitoringSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Users if not exist
        $user = User::firstOrCreate(
            ['email' => 'tech@example.com'],
            ['name' => 'Tech Support', 'password' => bcrypt('password')]
        );

        // 2. Create Endpoints (some online, some offline)
        $endpoints = [];

        // Online Endpoint
        $online = Endpoint::create([
            'hostname' => 'DESKTOP-ONLINE-01',
            'api_token' => 'token-online-01',
            'status' => 'online',
            'last_seen_at' => now(),
            'public_ip' => '192.168.1.10',
            'local_ip' => '10.0.0.10',
            'os_info' => 'Windows 11 Pro',
            'hardware_summary' => ['cpu' => 'Intel i7', 'ram' => '16GB'],
            'rustdesk_id' => '111222333',
        ]);
        $endpoints[] = $online;

        // Offline Endpoint
        $offline = Endpoint::create([
            'hostname' => 'SERVER-OFFLINE-02',
            'api_token' => 'token-offline-02',
            'status' => 'online', // DB says online but it's actually offline
            'last_seen_at' => now()->subHours(2),
            'public_ip' => '192.168.1.20',
            'local_ip' => '10.0.0.20',
            'os_info' => 'Ubuntu 22.04',
            'hardware_summary' => ['cpu' => 'AMD EPYC', 'ram' => '64GB'],
        ]);
        $endpoints[] = $offline;

        // 3. Generate Metrics for the Online Endpoint (Last 24 hours)
        $now = now();
        for ($i = 0; $i < 24; $i++) {
            Metric::create([
                'endpoint_id' => $online->id,
                'cpu_usage' => rand(10, 80),
                'ram_usage' => rand(30, 60),
                'ram_total' => 16,
                'disk_usage' => rand(40, 50),
                'created_at' => $now->copy()->subHours($i),
                'updated_at' => $now->copy()->subHours($i),
            ]);
        }

        // 4. Create Tickets
        Ticket::create([
            'title' => 'Printer not working',
            'description' => 'User cannot print from Tray 2',
            'status' => 'open',
            'priority' => 'medium',
            'user_id' => $user->id,
            'endpoint_id' => $online->id,
        ]);

        Ticket::create([
            'title' => 'System Slow',
            'description' => 'High CPU usage reported',
            'status' => 'in_progress',
            'priority' => 'high',
            'user_id' => $user->id,
            'endpoint_id' => $offline->id,
        ]);

        Ticket::create([
            'title' => 'Software Install',
            'description' => 'Install Office 365',
            'status' => 'resolved',
            'priority' => 'low',
            'user_id' => $user->id,
            'assigned_to' => $user->id,
        ]);
    }
}
