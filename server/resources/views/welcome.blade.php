<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema RMM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900">

    <!-- Navbar -->
    <nav class="border-b border-gray-100 bg-white/80 backdrop-blur-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-600 text-white p-1.5 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">Sistema RMM</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-500 hover:text-gray-900">Características</a>
                    <a href="{{ url('/portal') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">Centro de Ayuda</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Ir al Dashboard &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">Iniciar Sesión</a>
                            <!-- Assuming we don't have public registration enabled by default for an RMM, but user asked for SaaS model -->
                            <!-- If registration is enabled in routes, show it -->
                            <!-- <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">Comenzar Gratis</a> -->
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                Gestión IT Inteligente <br>
                <span class="text-indigo-600">para Empresas Modernas</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 mb-10">
                Monitoreo en tiempo real, soporte remoto y gestión de activos en una sola plataforma unificada.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ url('/admin') }}" class="px-8 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Acceso Administrativo
                </a>
                <a href="{{ url('/portal') }}" class="px-8 py-3 rounded-xl bg-white text-gray-700 border border-gray-200 font-semibold hover:bg-gray-50 transition">
                    Reportar Incidente
                </a>
            </div>
        </div>
        
        <!-- Abstract Background -->
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 opacity-30 pointer-events-none">
            <div class="absolute top-[20%] left-[20%] w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-[20%] right-[20%] w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[20%] left-[30%] w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <!-- Features Grid -->
    <div id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Potencia tu Infraestructura</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Todo lo que necesitas para gestionar IT</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Monitoreo RMM</h3>
                    <p class="text-gray-500 leading-relaxed">Supervisa el estado de todos tus dispositivos en tiempo real. CPU, RAM, Disco y conectividad.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Soporte Remoto</h3>
                    <p class="text-gray-500 leading-relaxed">Conexión instantánea con RustDesk. Resuelve problemas sin desplazamientos.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Helpdesk Integrado</h3>
                    <p class="text-gray-500 leading-relaxed">Portal de autoservicio para empleados. Gestión de tickets con SLAs y priorización.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} Sistema RMM. Todos los derechos reservados.</p>
            <div class="flex gap-6">
                <a href="#" class="text-gray-400 hover:text-gray-600 text-sm">Privacidad</a>
                <a href="#" class="text-gray-400 hover:text-gray-600 text-sm">Términos</a>
                <a href="#" class="text-gray-400 hover:text-gray-600 text-sm">Contacto</a>
            </div>
        </div>
    </footer>

</body>
</html>
