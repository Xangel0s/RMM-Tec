<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_ticket_logs_audit_entry(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'Printer not working',
            'description' => 'User cannot print from Tray 2',
            'status' => 'open',
            'priority' => 'medium',
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($ticket->id);

        $log = AuditLog::latest()->first();
        $this->assertNotNull($log);
        $this->assertSame('ticket_created', $log->action);
        $this->assertSame($user->id, $log->user_id);
        $this->assertIsArray($log->details);
        $this->assertSame($ticket->id, $log->details['ticket_id']);
        $this->assertSame($ticket->public_id, $log->details['public_id']);
        $this->assertSame($ticket->status, $log->details['status']);
        $this->assertSame($ticket->priority, $log->details['priority']);
    }

    public function test_updating_ticket_logs_audit_entry_with_changes(): void
    {
        $user = User::factory()->create();

        $ticket = Ticket::create([
            'title' => 'System Slow',
            'description' => 'High CPU usage reported',
            'status' => 'open',
            'priority' => 'high',
            'user_id' => $user->id,
        ]);

        $ticket->update([
            'status' => 'resolved',
            'priority' => 'low',
        ]);

        $log = AuditLog::where('action', 'ticket_updated')->latest()->first();
        $this->assertNotNull($log);
        $this->assertSame('ticket_updated', $log->action);
        $this->assertSame($user->id, $log->user_id);
        $this->assertIsArray($log->details);
        $this->assertSame($ticket->id, $log->details['ticket_id']);
        $this->assertArrayHasKey('changes', $log->details);
        $this->assertArrayHasKey('status', $log->details['changes']);
        $this->assertArrayHasKey('priority', $log->details['changes']);
        $this->assertSame('resolved', $log->details['changes']['status']);
        $this->assertSame('low', $log->details['changes']['priority']);
    }
}
