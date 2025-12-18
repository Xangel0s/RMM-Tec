<div>
<x-filament-panels::page>
    <div>
        <style>
        /* Professional Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937; 
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280; 
        }

        /* 1. STRICTLY HIDE NATIVE INPUT (First Design) */
        input[type="file"] {
            opacity: 0 !important;
            height: 0 !important;
            width: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            position: absolute !important;
            z-index: -1 !important;
            pointer-events: none !important;
            overflow: hidden !important;
            border: none !important;
        }
        
        /* Hide the WebKit button specifically too */
        ::-webkit-file-upload-button {
            display: none !important;
        }

        /* 2. ENHANCE FILEPOND (Second Design) */
        /* Add smooth entrance animation */
        .filepond--root {
            animation: fpFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            /* opacity: 0;  REMOVED to prevent visibility issues if animation fails */
            transform: translateY(20px);
            margin-bottom: 0 !important;
        }

        @keyframes fpFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* 3. CUSTOM DRAG & DROP SPINNER OVERLAY */
        .custom-loader-overlay {
            position: absolute;
            inset: 0;
            z-index: 50; /* LOWERED Z-INDEX to sit below Filament modal actions/header but above FilePond */
            background-color: rgba(31, 41, 55, 0.95); /* Gray-900 with opacity */
            backdrop-filter: blur(4px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .custom-loader-active {
            opacity: 1;
            pointer-events: auto;
        }
        
        /* Spinner SVG Animation */
        .custom-spinner {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
        }
        
        .custom-ring {
            stroke: #374151; /* Gray-700 */
            stroke-width: 4;
            fill: none;
        }
        
        .custom-arc {
            stroke: #f59e0b; /* Amber-500 (Primary) */
            stroke-width: 4;
            stroke-linecap: round;
            fill: none;
            transform-origin: center;
            animation: customSpin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }
        
        @keyframes customSpin {
            0% {
                transform: rotate(0deg);
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 100, 200;
                stroke-dashoffset: -15;
            }
            100% {
                transform: rotate(360deg);
                stroke-dasharray: 1, 200;
                stroke-dashoffset: -125;
            }
        }
        
        .custom-loader-text {
            color: #f3f4f6; /* Gray-100 */
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.025em;
        }

        /* Force dark mode styles for this component */
        .fm-container {
            position: relative; /* Context for absolute positioning */
            min-height: 200px;  /* Ensure visibility when empty */
            background-color: #111827; /* gray-900 */
            border: 1px solid #374151; /* gray-700 */
            border-radius: 0.75rem;
            overflow: hidden; /* Constrain overlay to corners */
            color: #e5e7eb; /* gray-200 */
        }
        .fm-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            background-color: rgba(17, 24, 39, 0.5);
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .fm-breadcrumb {
            display: flex;
            align-items: center;
            flex: 1;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-family: monospace;
            overflow-x: auto;
        }
        .fm-table {
            width: 100%;
            border-collapse: collapse;
            font-family: monospace;
            font-size: 0.875rem;
        }
        .fm-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            background-color: rgba(255, 255, 255, 0.05);
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .fm-table td {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .fm-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .fm-icon {
            width: 1.25rem;
            height: 1.25rem;
            display: inline-block;
            vertical-align: middle;
            margin-right: 0.5rem;
        }
        .fm-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .fm-btn-gray {
            background-color: #374151;
            color: white;
            border: none;
        }
        .fm-btn-gray:hover {
            background-color: #4b5563;
        }
        .text-blue { color: #60a5fa; }
        .text-yellow { color: #fbbf24; }
        .text-gray { color: #9ca3af; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Loading Overlay Styles */
        .fm-loading-overlay {
            position: absolute; /* Scoped to .fm-container */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            
            /* Layering: Overlay above content (0), Spinner naturally on top */
            z-index: 50; 
            
            /* Visuals */
            background-color: rgba(17, 24, 39, 0.5); /* Semi-transparent dark */
            backdrop-filter: blur(4px);
            
            display: flex; /* Centering */
            align-items: center;
            justify-content: center;
            flex-direction: column;
            
            /* Transitions */
            transition: opacity 0.3s ease;
            opacity: 0;
            pointer-events: none; /* Pass-through when hidden */
        }

        .fm-loading-active {
            opacity: 1 !important;
            pointer-events: auto !important; /* Block interaction */
        }
        
        /* Spinner Animation */
        .fm-spinner {
            width: 4rem;
            height: 4rem;
            border: 4px solid rgba(96, 165, 250, 0.3);
            border-radius: 50%;
            border-top-color: #60a5fa; /* Blue-400 */
            animation: spin 1s linear infinite;
            margin-bottom: 1.5rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    {{-- Toolbar --}}
    <div class="fm-toolbar">
        <div class="fm-breadcrumb">
            <x-filament::icon icon="heroicon-m-computer-desktop" class="fm-icon text-gray" style="margin-right: 8px;" />
            <span style="color: #e5e7eb;">{{ $currentPath }}</span>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button 
                wire:click="goUp" 
                wire:loading.attr="disabled"
                wire:target="goUp"
                class="fm-btn fm-btn-gray" 
                title="Subir Nivel" 
                @if(strlen($currentPath) <= 3) disabled style="opacity: 0.5; cursor: not-allowed;" @endif
            >
                <x-filament::icon icon="heroicon-m-arrow-up" class="fm-icon" />
            </button>
            <button 
                wire:click="refreshFiles" 
                wire:loading.attr="disabled"
                wire:target="refreshFiles"
                class="fm-btn fm-btn-gray" 
                title="Recargar"
            >
                <div wire:loading.remove wire:target="refreshFiles">
                    <x-filament::icon icon="heroicon-m-arrow-path" class="fm-icon" />
                    <span style="margin-left: 4px;">Recargar</span>
                </div>
                <div wire:loading wire:target="refreshFiles">
                    <x-filament::icon icon="heroicon-m-arrow-path" class="fm-icon animate-spin" />
                    <span style="margin-left: 4px;">...</span>
                </div>
            </button>
            <button wire:click="toggleViewMode" class="fm-btn fm-btn-gray" title="Cambiar Vista">
                <x-filament::icon icon="{{ $viewMode === 'grid' ? 'heroicon-m-list-bullet' : 'heroicon-m-squares-2x2' }}" class="fm-icon" />
            </button>
        </div>
    </div>

    <div class="fm-container" wire:poll.300ms="checkStatus">
        {{-- Loading Overlay --}}
        <div class="fm-loading-overlay {{ $loading ? 'fm-loading-active' : '' }}" wire:loading.class="fm-loading-active" wire:target="navigateTo, goUp, refreshFiles, toggleViewMode" role="alert" aria-busy="true" aria-label="Cargando">
            <div class="fm-spinner"></div>
            
            {{-- Generic Loading Message --}}
            <div wire:loading.remove wire:target="validateDuplicates">
                <h3 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 0.5rem; text-align: center;">Procesando su solicitud...</h3>
                <p style="color: #d1d5db; font-size: 1rem; text-align: center;">Accediendo al sistema de archivos remoto</p>
            </div>

            {{-- Specific Message for Duplicate Validation (Placeholder for future) --}}
            <div wire:loading.flex wire:target="validateDuplicates" style="flex-direction: column; align-items: center; display: none;">
                <h3 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 0.5rem; text-align: center;">Verificando Duplicados...</h3>
                <p style="color: #d1d5db; font-size: 1rem; text-align: center;">Analizando integridad de archivos</p>
            </div>
        </div>

        @if(empty($files) && !$loading)
            <div style="padding: 3rem; text-align: center;">
                <x-filament::icon icon="heroicon-o-folder-open" style="width: 3rem; height: 3rem; color: #4b5563; margin: 0 auto 1rem;" />
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Directorio Vacío o Sin Cargar</h3>
                <p style="color: #9ca3af; margin-bottom: 1.5rem;">Haga clic en "Recargar" para obtener los archivos.</p>
                <button wire:click="refreshFiles" class="fm-btn fm-btn-gray">Cargar Archivos</button>
            </div>
        @else
            @if($viewMode === 'list')
                <div style="overflow-x: auto;">
                    <table class="fm-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Name</th>
                                <th class="text-right">Perm</th>
                                <th class="text-right">Modified</th>
                                <th class="text-right">Size</th>
                                <th class="text-center">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(strlen($currentPath) > 3)
                                <tr wire:click="goUp" style="cursor: pointer;">
                                    <td>
                                        <x-filament::icon icon="heroicon-s-folder" class="fm-icon text-blue" />
                                        <strong>..</strong>
                                    </td>
                                    <td class="text-right text-gray">-</td>
                                    <td class="text-right text-gray">-</td>
                                    <td class="text-right text-gray">-</td>
                                    <td class="text-center text-gray">DIR</td>
                                </tr>
                            @endif

                            @foreach($files as $file)
                                <tr 
                                    @if($file['is_dir']) 
                                        wire:click="navigateTo('{{ $file['name'] }}')" 
                                        class="hover:bg-white/5 cursor-pointer"
                                    @endif
                                >
                                    <td>
                                        <x-filament::icon 
                                            icon="{{ $file['is_dir'] ? 'heroicon-s-folder' : 'heroicon-o-document' }}" 
                                            class="fm-icon {{ $file['is_dir'] ? 'text-blue' : 'text-gray' }}" 
                                        />
                                        @if($file['is_dir'])
                                            <span style="color: #fff; font-weight: 500;">{{ $file['name'] }}</span>
                                        @else
                                            <span style="color: #d1d5db;">{{ $file['name'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right text-gray">{{ $file['is_dir'] ? 'drwxr-xr-x' : '-rw-r--r--' }}</td>
                                    <td class="text-right text-gray">{{ $file['mod_time'] ?? '-' }}</td>
                                    <td class="text-right text-gray">{{ $file['is_dir'] ? '-' : number_format($file['size']) . ' b' }}</td>
                                    <td class="text-center text-gray">{{ $file['is_dir'] ? 'DIR' : 'FILE' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem;">
                    @if(strlen($currentPath) > 3)
                        <div wire:click="goUp" style="cursor: pointer; text-align: center; padding: 1rem; border-radius: 0.5rem;" class="hover:bg-white/5">
                            <x-filament::icon icon="heroicon-s-folder" style="width: 3rem; height: 3rem; color: #60a5fa; margin: 0 auto 0.5rem;" />
                            <div style="font-weight: bold; font-size: 0.875rem;">..</div>
                        </div>
                    @endif

                    @foreach($files as $file)
                        <div 
                            @if($file['is_dir']) 
                                wire:click="navigateTo('{{ $file['name'] }}')" 
                                style="cursor: pointer;" 
                            @endif
                            style="text-align: center; padding: 1rem; border-radius: 0.5rem; transition: background 0.2s;"
                            class="hover:bg-white/5"
                            title="{{ $file['name'] }}"
                        >
                            @if($file['is_dir'])
                                <x-filament::icon 
                                    icon="heroicon-s-folder" 
                                    style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; color: #60a5fa;" 
                                />
                            @else
                                <x-filament::icon 
                                    icon="heroicon-o-document" 
                                    style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; color: #9ca3af;" 
                                />
                            @endif
                            <div style="font-size: 0.875rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $file['name'] }}
                            </div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                                {{ $file['is_dir'] ? 'DIR' : number_format($file['size']) . ' b' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Monitor DOM changes for FilePond initialization
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes.length) {
                        // Check if FilePond root was added
                        const filePonds = document.querySelectorAll('.filepond--root');
                        
                        if (filePonds.length > 0) {
                            filePonds.forEach(pond => {
                                // 1. Smooth Scroll
                                setTimeout(() => {
                                    pond.scrollIntoView({ 
                                        behavior: 'smooth', 
                                        block: 'center',
                                        inline: 'nearest'
                                    });
                                }, 300);

                                // 2. Inject Custom Loader Overlay (If not exists)
                                // We need to find the wrapper (.filament-forms-file-upload-component)
                                const wrapper = pond.closest('.filament-forms-file-upload-component');
                                if (wrapper && !wrapper.querySelector('.custom-loader-overlay')) {
                                    // Make wrapper relative for absolute positioning
                                    wrapper.style.position = 'relative';
                                    
                                    // Create a Single Unified Stateful Loader
                                    const loaderHTML = `
                                        <div class="custom-loader-overlay custom-loader-active" data-state="initial">
                                            <svg class="custom-spinner" viewBox="0 0 50 50">
                                                <circle class="custom-ring" cx="25" cy="25" r="20"></circle>
                                                <circle class="custom-arc" cx="25" cy="25" r="20"></circle>
                                            </svg>
                                            <p class="custom-loader-text">Iniciando subida...</p>
                                        </div>
                                    `;
                                    wrapper.insertAdjacentHTML('beforeend', loaderHTML);
                                    
                                    const loader = wrapper.querySelector('.custom-loader-overlay');
                                    const text = loader.querySelector('.custom-loader-text');
                                    
                                    // Helper to update state
                                    const updateState = (state, message) => {
                                        loader.dataset.state = state;
                                        if (message) text.textContent = message;
                                        
                                        if (state === 'hidden') {
                                            loader.classList.remove('custom-loader-active');
                                        } else {
                                            loader.classList.add('custom-loader-active');
                                        }
                                    };

                                    // 1. Initial Load State (Auto-hide after 800ms)
                                    setTimeout(() => {
                                        if (loader.dataset.state === 'initial') {
                                            updateState('hidden');
                                        }
                                    }, 800);
                                    
                                    // 2. Bind FilePond Events
                                    
                                    // Event: Start Upload / Drop (Processing State)
                                    pond.addEventListener('FilePond:addfile', (e) => {
                                        if (!e.detail.error) {
                                            updateState('processing', 'Procesando imagen...');
                                        }
                                    });

                                    // Event: File Preparation (Optional)
                                    pond.addEventListener('FilePond:preparefile', () => {
                                         updateState('processing', 'Preparando archivo...');
                                    });

                                    // Event: Complete (Success State -> Hidden)
                                    pond.addEventListener('FilePond:processfile', () => {
                                        updateState('complete', '¡Completado!');
                                        setTimeout(() => {
                                            updateState('hidden');
                                        }, 800); // Show success message briefly
                                    });

                                    // Event: Error (Error State -> Hidden)
                                    pond.addEventListener('FilePond:error', () => {
                                        updateState('error', 'Error en la carga');
                                        setTimeout(() => {
                                            updateState('hidden');
                                        }, 1500); // Show error message briefly
                                    });
                                }
                            });
                        }
                    }
                });
            });

            // Observe the body or modal container
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>
    </div>
</x-filament-panels::page>
</div>