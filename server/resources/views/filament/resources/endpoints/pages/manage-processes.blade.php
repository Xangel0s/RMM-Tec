<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Toolbar: Search --}}
        <div class="flex justify-end">
            <div class="w-full max-w-xs">
                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                    <x-filament::input
                        type="search"
                        wire:model.live="search"
                        placeholder="Buscar por nombre o PID..."
                    />
                </x-filament::input.wrapper>
            </div>
        </div>

        <div 
            class="fi-ta-ctn ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl bg-white dark:bg-gray-900 shadow-sm overflow-hidden" 
            wire:poll.5s="checkStatus"
        >
            @if(empty($processes) && !$loading)
                <x-filament::section icon="heroicon-o-cpu-chip">
                    <x-slot name="heading">
                        Sin información de procesos
                    </x-slot>

                    <x-slot name="description">
                        Haga clic en el botón <span class="font-bold text-primary-500">"Actualizar Procesos"</span> situado arriba para cargar la lista de procesos activos del endpoint.
                    </x-slot>
                </x-filament::section>
            @else
                <div class="fi-ta-content relative divide-y divide-gray-200 dark:divide-white/5 overflow-x-auto">
                    <table class="fi-ta-table w-full text-start divide-y divide-gray-200 dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                {{-- Headers with Sorting --}}
                                @foreach([
                                    'pid' => 'PID', 
                                    'name' => 'Nombre', 
                                    'user' => 'Usuario', 
                                    'memory' => 'Memoria', 
                                    'cpu' => 'CPU'
                                ] as $col => $label)
                                    <th 
                                        class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 cursor-pointer hover:bg-gray-100 dark:hover:bg-white/5 transition group" 
                                        wire:click="sort('{{ $col }}')"
                                    >
                                        <div class="flex items-center gap-x-1 {{ in_array($col, ['memory', 'cpu']) ? 'justify-end' : '' }}">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $label }}
                                            </span>
                                            
                                            <span class="transition {{ $sortColumn === $col ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}">
                                                <x-filament::icon
                                                    icon="{{ $sortDirection === 'asc' && $sortColumn === $col ? 'heroicon-m-chevron-up' : 'heroicon-m-chevron-down' }}"
                                                    class="w-3 h-3 text-gray-500"
                                                />
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6 text-center w-1">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($this->filteredProcesses as $process)
                                <tr class="fi-ta-row hover:bg-gray-50 dark:hover:bg-white/5 transition duration-75">
                                    {{-- PID --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="px-3 py-4 text-sm font-mono text-gray-500 dark:text-gray-400">
                                                {{ $process['pid'] ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Name --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="px-3 py-4 text-sm font-medium text-gray-900 dark:text-white break-all">
                                                {{ $process['name'] ?? 'Unknown' }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- User --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                {{ $process['user'] ?? '-' }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Memory --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="px-3 py-4 text-sm text-right font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                {{ $process['memory'] ?? '-' }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- CPU --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="flex justify-end px-3 py-4">
                                                @php
                                                    $cpu = (float)($process['cpu'] ?? 0);
                                                    $color = $cpu > 50 ? 'danger' : ($cpu > 20 ? 'warning' : 'gray');
                                                @endphp
                                                <x-filament::badge :color="$color">
                                                    {{ number_format($cpu, 2) }}%
                                                </x-filament::badge>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="fi-ta-col-wrp">
                                            <div class="flex justify-center px-3 py-4">
                                                <x-filament::icon-button
                                                    icon="heroicon-m-trash"
                                                    color="danger"
                                                    tooltip="Terminar Proceso"
                                                    wire:click="killProcess('{{ $process['pid'] ?? '' }}')"
                                                    wire:confirm="¿Estás seguro de terminar el proceso {{ $process['name'] ?? '' }} (PID: {{ $process['pid'] ?? '' }})?"
                                                />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No se encontraron procesos que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
