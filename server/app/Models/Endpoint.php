<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'hostname',
        'public_ip',
        'local_ip',
        'os_info',
        'hardware_summary',
        'rustdesk_id',
        'last_seen_at',
        'status',
        'api_token',
    ];

    protected $casts = [
        'hardware_summary' => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function commands()
    {
        return $this->hasMany(Command::class);
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }
}
