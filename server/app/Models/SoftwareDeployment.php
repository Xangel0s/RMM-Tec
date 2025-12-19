<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareDeployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_name',
        'installer_url',
        'endpoints',
        'status',
        'deployment_date',
    ];

    protected $casts = [
        'endpoints' => 'array',
        'deployment_date' => 'datetime',
    ];
}
