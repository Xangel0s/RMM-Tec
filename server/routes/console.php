<?php

use App\Models\AlertIncident;
use App\Models\Endpoint;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Clean old resolved incidents (older than 30 days)
Schedule::call(function () {
    $count = AlertIncident::whereNotNull('resolved_at')
        ->where('resolved_at', '<', now()->subDays(30))
        ->delete();

    if ($count > 0) {
        Log::info("Scheduler: Cleaned $count old resolved incidents.");
    }
})->daily();

// 2. Check for Offline Devices (Offline > 10 minutes)
Schedule::call(function () {
    $offlineThreshold = now()->subMinutes(10);

    $offlineEndpoints = Endpoint::where('last_seen_at', '<', $offlineThreshold)
        ->where('status', '!=', 'offline') // Only if not already marked offline
        ->get();

    foreach ($offlineEndpoints as $endpoint) {
        // Mark as offline in DB (though UI calculates it dynamically, this helps with reporting)
        // Note: Our Heartbeat controller sets it to 'online' on every ping.
        // We can optionally update the status field here if we want the DB to reflect reality.
        // $endpoint->update(['status' => 'offline']);

        Log::warning("Scheduler: Device {$endpoint->hostname} is OFFLINE (Last seen: {$endpoint->last_seen_at})");

        // TODO: Create an AlertIncident for "Device Offline" if not exists
    }
})->everyFiveMinutes();

// 3. Daily Report Log
Schedule::call(function () {
    $total = Endpoint::count();
    $online = Endpoint::where('last_seen_at', '>=', now()->subMinutes(5))->count();

    Log::info("Daily Report: $online / $total devices online.");
})->dailyAt('08:00');
