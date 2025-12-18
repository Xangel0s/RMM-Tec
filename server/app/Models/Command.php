<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint_id',
        'command',
        'status',
        'output',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
