<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'title',
        'description',
        'status',
        'priority',
        'department',
        'user_id',
        'assigned_to',
        'endpoint_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->public_id)) {
                $ticket->public_id = 'TKT-'.strtoupper(Str::random(8));

                // Ensure uniqueness
                while (static::where('public_id', $ticket->public_id)->exists()) {
                    $ticket->public_id = 'TKT-'.strtoupper(Str::random(8));
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
