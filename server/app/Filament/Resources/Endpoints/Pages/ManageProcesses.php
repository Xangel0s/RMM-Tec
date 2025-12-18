<?php

namespace App\Filament\Resources\Endpoints\Pages;

use App\Filament\Resources\Endpoints\EndpointResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\Command;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ManageProcesses extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EndpointResource::class;

    protected string $view = 'filament.resources.endpoints.pages.manage-processes';
    
    protected static ?string $title = 'Administrador de Procesos';

    public $processes = [];
    public $loading = false;
    public $lastCommandId = null;

    // Sorting and Searching
    public $search = '';
    public $sortColumn = 'cpu';
    public $sortDirection = 'desc';

    public function updatedSearch()
    {
        // Reset to first page if we had pagination (optional)
    }

    public function sort($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function getFilteredProcessesProperty()
    {
        $data = $this->processes;

        // Filtering
        if (!empty($this->search)) {
            $data = array_filter($data, function ($item) {
                return stripos($item['name'] ?? '', $this->search) !== false 
                    || stripos((string)($item['pid'] ?? ''), $this->search) !== false;
            });
        }

        // Sorting
        usort($data, function ($a, $b) {
            $col = $this->sortColumn;
            
            $valA = $a[$col] ?? 0;
            $valB = $b[$col] ?? 0;

            // Handle numeric columns specifically
            if (in_array($col, ['cpu', 'pid'])) {
                $valA = (float) $valA;
                $valB = (float) $valB;
            }
            
            // Handle memory (remove ' MB' and parse float)
            if ($col === 'memory') {
                 $valA = (float) filter_var($valA, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                 $valB = (float) filter_var($valB, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }

            if ($valA == $valB) return 0;
            
            $result = ($valA < $valB) ? -1 : 1;
            
            return $this->sortDirection === 'asc' ? $result : -$result;
        });

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Actualizar Procesos')
                ->action('refreshProcesses')
                ->color('warning')
                ->icon('heroicon-m-arrow-path')
                ->disabled(fn () => $this->loading),
        ];
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function refreshProcesses()
    {
        $this->loading = true;
        
        $command = $this->record->commands()->create([
            'command' => '__GET_PROCESSES__',
            'status' => 'pending',
        ]);
        
        $this->lastCommandId = $command->id;
        
        Notification::make()->title('Solicitando lista de procesos...')->info()->send();
    }
    
    public function checkStatus() 
    {
        if (!$this->lastCommandId) return;
        
        $cmd = Command::find($this->lastCommandId);
        
        if (!$cmd) return;

        if ($cmd->status === 'completed') {
            $data = json_decode($cmd->output, true);
            if (is_array($data)) {
                $this->processes = $data;
                Notification::make()->title('Procesos actualizados')->success()->send();
            } else {
                Notification::make()->title('Error al decodificar respuesta')->danger()->send();
            }
            $this->loading = false;
            $this->lastCommandId = null;
        } elseif ($cmd->status === 'failed') {
             $this->loading = false;
             $this->lastCommandId = null;
             Notification::make()->title('El agente reportó un error')->danger()->send();
        }
    }

    public function killProcess($pid)
    {
        $this->record->commands()->create([
            'command' => "__KILL_PROCESS__ $pid",
            'status' => 'pending',
        ]);
        
        Notification::make()->title("Enviada orden de finalizar PID $pid")->success()->send();
    }
}
