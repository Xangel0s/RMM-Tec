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
            $text = strtolower(($ticket->title ?? '') . ' ' . ($ticket->description ?? ''));
            if (empty($ticket->priority)) {
                $ticket->priority = str_contains($text, 'critical') || str_contains($text, 'urgent') || str_contains($text, 'fallo') ? 'high' : (str_contains($text, 'error') ? 'medium' : 'low');
            }
            if (empty($ticket->department)) {
                $ticket->department = str_contains($text, 'red') || str_contains($text, 'network') ? 'Networking' :
                    (str_contains($text, 'printer') ? 'Printing' :
                    (str_contains($text, 'software') || str_contains($text, 'app') ? 'Software' : 'General'));
            }
        });

        static::created(function ($ticket) {
            AuditLog::create([
                'user_id' => $ticket->user_id,
                'action' => 'ticket_created',
                'endpoint_id' => $ticket->endpoint_id,
                'details' => [
                    'ticket_id' => $ticket->id,
                    'public_id' => $ticket->public_id,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                ],
            ]);
        });

        static::updated(function ($ticket) {
            AuditLog::create([
                'user_id' => $ticket->user_id,
                'action' => 'ticket_updated',
                'endpoint_id' => $ticket->endpoint_id,
                'details' => [
                    'ticket_id' => $ticket->id,
                    'changes' => $ticket->getChanges(),
                ],
            ]);
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
