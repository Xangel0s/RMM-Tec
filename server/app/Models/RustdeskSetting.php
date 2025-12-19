<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RustdeskSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_url',
        'relay_server',
        'api_key',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
