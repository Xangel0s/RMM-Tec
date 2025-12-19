<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'command',
        'cron_expression',
        'endpoints',
        'enabled',
        'created_by',
        'last_run_at',
        'next_run_at',
    ];

    protected $casts = [
        'endpoints' => 'array',
        'enabled' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
