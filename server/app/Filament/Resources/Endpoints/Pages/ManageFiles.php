<?php

namespace App\Filament\Resources\Endpoints\Pages;

use App\Filament\Resources\Endpoints\EndpointResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;

use App\Models\Command;
use Filament\Notifications\Notification;

class ManageFiles extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EndpointResource::class;

    public function getView(): string
    {
        return 'filament.resources.endpoints.pages.manage-files';
    }
    
    protected static ?string $title = 'Explorador de Archivos';
    
    public $currentPath = 'C:\\'; 
    public $files = [];
    public $directoryCache = []; // Cache to store visited directories
    public $loading = false;
    public $lastCommandId = null;
    public $viewMode = 'grid'; // Default to grid for better UX

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        // Try to load initial path from cache if exists (unlikely on mount, but good practice)
        if (isset($this->directoryCache[$this->currentPath])) {
            $this->files = $this->directoryCache[$this->currentPath];
        }
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Recargar')
                ->icon('heroicon-m-arrow-path')
                ->color('gray')
                ->action(function() {
                    // Force refresh: clear cache for current path
                    unset($this->directoryCache[$this->currentPath]);
                    $this->refreshFiles();
                }),
                
            Action::make('upload')
                ->label('Subir Archivo')
                ->modalHeading('Subir Archivo')
                ->modalSubmitActionLabel('Subir')
                ->modalCancelActionLabel('Cancelar')
                ->icon('heroicon-m-arrow-up-tray')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('attachment')
                        ->label('Seleccionar Archivo')
                        ->required()
                        ->disk('public')
                        ->directory('uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $filePath = $data['attachment'];
                    $url = asset('storage/' . $filePath);
                    $fileName = basename($filePath);
                    
                    // Construct target path (Windows style for now)
                    $separator = str_contains($this->currentPath, '/') ? '/' : '\\';
                    $targetPath = rtrim($this->currentPath, $separator) . $separator . $fileName;
                    
                    // Create command: __DOWNLOAD_FILE__ "URL" "TARGET"
                    // We use a pipe | as a safer delimiter to avoid quote hell if possible, 
                    // but standard quotes are better if we parse them right.
                    // Let's stick to quotes and escape them if needed.
                    $commandStr = sprintf('__DOWNLOAD_FILE__ "%s" "%s"', $url, $targetPath);
                    
                    $this->record->commands()->create([
                        'command' => $commandStr,
                        'status' => 'pending',
                    ]);
                    
                    $this->lastCommandId = null; // Don't track this command immediately for file list refresh
                    Notification::make()->title('Subida iniciada...')->success()->send();
                }),
        ];
    }

    public function refreshFiles()
    {
        // Check cache first
        if (isset($this->directoryCache[$this->currentPath])) {
            $this->files = $this->directoryCache[$this->currentPath];
            $this->loading = false;
            return;
        }

        $this->loading = true;
        
        $command = $this->record->commands()->create([
            'command' => '__LIST_DIRECTORY__ ' . $this->currentPath,
            'status' => 'pending',
        ]);
        
        $this->lastCommandId = $command->id;
    }

    public function checkStatus() 
    {
        if (!$this->lastCommandId) return;
        
        $cmd = Command::find($this->lastCommandId);
        
        if (!$cmd) return;

        // TIMEOUT OR STUCK LOGIC
        // If the command is older than 15 seconds and still pending/processing, consider it stuck.
        if (in_array($cmd->status, ['pending', 'processing']) && $cmd->created_at->diffInSeconds(now()) > 15) {
             $this->handleStuckState();
             return;
        }

        if ($cmd->status === 'completed') {
            $response = json_decode($cmd->output, true);
            
            // Handle new structure {path: "...", files: [...]}
            if (is_array($response) && isset($response['files'])) {
                $this->files = $response['files'];
                $this->currentPath = $response['path']; // Sync path with reality!
                
                // Save to cache
                $this->directoryCache[$this->currentPath] = $this->files;
                
                Notification::make()->title('Archivos actualizados')->success()->send();
            } 
            // Handle legacy structure (just array of files)
            elseif (is_array($response)) {
                 $this->files = $response;
                 $this->directoryCache[$this->currentPath] = $response;
                 Notification::make()->title('Archivos actualizados')->success()->send();
            } else {
                // If response is null/invalid but command completed, it might be an empty dir or error
                // Fallback to empty list to avoid getting stuck
                $this->files = [];
                Notification::make()->title('Directorio vacío o respuesta inválida')->warning()->send();
            }
            $this->loading = false;
            $this->lastCommandId = null;
        } elseif ($cmd->status === 'failed') {
             $this->handleFailedState($cmd->output);
        }
    }

    protected function handleStuckState()
    {
        $this->loading = false;
        $this->lastCommandId = null;
        
        Notification::make()
            ->title('Tiempo de espera agotado')
            ->body('El agente tardó demasiado en responder. Volviendo al directorio raíz por seguridad.')
            ->warning()
            ->send();
            
        // Reset to safe root
        $this->currentPath = 'C:\\';
        $this->refreshFiles();
    }

    protected function handleFailedState($errorMessage = null)
    {
        $this->loading = false;
        $this->lastCommandId = null;
        $errorMessage = $errorMessage ?? 'Error desconocido';
        
        Notification::make()
           ->title('Error al listar directorio')
           ->body($errorMessage)
           ->danger()
           ->send();
           
        // If error contains "Access is denied" or "cannot find", maybe go up or root?
        // For now, let's just let user decide or manually go up. 
        // But if the user request is "should go back to C:/", let's do it on critical failures too if path seems invalid.
    }

    public function navigateTo($folderName)
    {
        // Simple path concatenation logic (for Windows mainly based on currentPath)
        $separator = str_contains($this->currentPath, '/') ? '/' : '\\';
        
        // Fix double separators or missing ones
        $base = rtrim($this->currentPath, $separator);
        if ($base === '' && $separator === '/') $base = ''; // Root linux case
        
        $newPath = $base . $separator . $folderName;
        
        $this->currentPath = $newPath;
        $this->refreshFiles();
    }

    public function navigateToPath($path)
    {
        $this->currentPath = $path;
        $this->refreshFiles();
    }

    public function goUp()
    {
        $separator = str_contains($this->currentPath, '/') ? '/' : '\\';
        $parts = explode($separator, rtrim($this->currentPath, $separator));
        array_pop($parts);
        
        $newPath = implode($separator, $parts);
        if (empty($newPath) && $separator == '\\') $newPath = 'C:\\'; // Basic fallback
        if (empty($newPath) && $separator == '/') $newPath = '/';

        $this->currentPath = $newPath;
        $this->refreshFiles();
    }
}
