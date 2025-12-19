<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico - Estado del Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center" x-data="portalApp()">

    <div class="max-w-md w-full px-4">
        
        <!-- Header Minimalista -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Centro de Ayuda</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona y consulta tus tickets de soporte</p>
        </div>

        <!-- Pantalla Principal (Menú) -->
        <div x-show="view === 'menu'" class="space-y-4">
            <!-- Card: Crear Ticket -->
            <button @click="view = 'create'" class="w-full group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">Crear Nuevo Ticket</h3>
                        <p class="text-sm text-gray-500 mt-1">Reportar un nuevo incidente</p>
                    </div>
                    <div class="bg-indigo-50 p-2 rounded-lg group-hover:bg-indigo-100">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </div>
            </button>

            <!-- Card: Verificar Ticket -->
            <button @click="view = 'track'" class="w-full group bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">Verificar Mis Tickets</h3>
                        <p class="text-sm text-gray-500 mt-1">Consultar estado con tu ID</p>
                    </div>
                    <div class="bg-indigo-50 p-2 rounded-lg group-hover:bg-indigo-100">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </button>
        </div>

        <!-- Pantalla: Crear Ticket -->
        <div x-show="view === 'create'" x-cloak>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <button @click="view = 'menu'" class="text-sm text-gray-500 hover:text-gray-900 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver
                </button>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Nuevo Reporte</h2>
                <form @submit.prevent="submitTicket" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Tu Email</label>
                        <input type="email" x-model="form.email" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Título del Problema</label>
                        <input type="text" x-model="form.title" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-1">Descripción</label>
                        <textarea x-model="form.description" rows="3" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2.5"></textarea>
                    </div>
                    <button type="submit" :disabled="loading" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                        <span x-show="!loading">Enviar Ticket</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Pantalla: Verificar Ticket -->
        <div x-show="view === 'track'" x-cloak>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <button @click="view = 'menu'; ticketResult = null" class="text-sm text-gray-500 hover:text-gray-900 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver
                </button>
                
                <h2 class="text-lg font-bold text-gray-900 mb-4">Consultar Estado</h2>
                <form @submit.prevent="trackTicket" class="flex gap-2 mb-6">
                    <input type="text" x-model="trackId" placeholder="Ej: TKT-A1B2C3D4" required class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2.5 uppercase">
                    <button type="submit" :disabled="loading" class="px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
                        Buscar
                    </button>
                </form>

                <!-- Resultado de Búsqueda -->
                <div x-show="ticketResult" class="border-t pt-4 mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">ID Ticket</span>
                        <span class="font-mono text-sm font-bold text-gray-900" x-text="ticketResult?.id"></span>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-md font-medium text-gray-900" x-text="ticketResult?.title"></h3>
                        <p class="text-xs text-gray-500" x-text="'Actualizado ' + ticketResult?.updated_at"></p>
                    </div>
                    
                    <div class="rounded-lg p-3 text-center" 
                         :class="{
                             'bg-green-100 text-green-800': ticketResult?.status === 'En línea',
                             'bg-blue-100 text-blue-800': ticketResult?.status === 'Gestionado'
                         }">
                        <span class="text-sm font-bold uppercase tracking-wider" x-text="ticketResult?.status"></span>
                    </div>
                </div>

                <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg text-center" x-text="error"></div>
            </div>
        </div>

        <!-- Modal de Éxito -->
        <div x-show="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-lg p-6 max-w-sm w-full text-center shadow-xl">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">¡Ticket Creado!</h3>
                <p class="text-sm text-gray-500 mb-4">Guarda este ID para consultar el estado de tu caso:</p>
                <div class="bg-gray-100 p-3 rounded-lg font-mono text-lg font-bold text-gray-900 select-all mb-6" x-text="createdTicketId"></div>
                <button @click="successModal = false; view = 'menu'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:text-sm">
                    Entendido
                </button>
            </div>
        </div>

    </div>

    <script>
        function portalApp() {
            return {
                view: 'menu', // menu, create, track
                loading: false,
                form: { email: '', title: '', description: '', priority: '', department: '' },
                trackId: '',
                ticketResult: null,
                error: null,
                successModal: false,
                createdTicketId: '',

                async submitTicket() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/portal/tickets', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.form)
                        });
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.createdTicketId = data.public_id;
                            this.successModal = true;
                            this.form = { email: '', title: '', description: '', priority: '', department: '' };
                        } else {
                            // Show validation errors properly
                            if (data.errors) {
                                alert('Error: ' + Object.values(data.errors).flat().join('\n'));
                            } else {
                                alert('Error: ' + (data.message || 'No se pudo crear el ticket'));
                            }
                        }
                    } catch (e) {
                        alert('Error de conexión');
                    } finally {
                        this.loading = false;
                    }
                },

                async trackTicket() {
                    this.loading = true;
                    this.error = null;
                    this.ticketResult = null;
                    try {
                        const response = await fetch('/api/portal/tickets/' + this.trackId, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.ticketResult = data;
                        } else {
                            this.error = 'Ticket no encontrado. Verifica el ID.';
                        }
                    } catch (e) {
                        this.error = 'Error de conexión';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
