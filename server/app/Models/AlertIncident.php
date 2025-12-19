<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertIncident extends Model
{
    protected $fillable = [
        'alert_rule_id',
        'endpoint_id',
        'triggered_at',
        'resolved_at',
        'message',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function alertRule()
    {
        return $this->belongsTo(AlertRule::class);
    }

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
