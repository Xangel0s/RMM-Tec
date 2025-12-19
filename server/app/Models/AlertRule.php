<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    protected $fillable = [
        'endpoint_id',
        'metric',
        'threshold',
        'duration_seconds',
        'action',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }

    public function incidents()
    {
        return $this->hasMany(AlertIncident::class);
    }
}
