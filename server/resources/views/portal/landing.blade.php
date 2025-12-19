<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Stickets - The ultimate SaaS platform for IT monitoring and support ticketing.">
    <title>Stickets - Intelligent IT Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(to right, #4F46E5, #9333EA);
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-indigo-600 text-white px-4 py-2 rounded-md z-50">Skip to main content</a>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 transition-all duration-300" x-data="{ scrolled: false }" :class="{ 'shadow-sm': scrolled }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="{{ url('/portal') }}" class="flex items-center gap-2 group" aria-label="Stickets Home">
                        <div class="bg-indigo-600 text-white p-1.5 rounded-lg group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">stickets</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#testimonials" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Stories</a>
                    <a href="#faq" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">FAQ</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="hidden md:block text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Log in</a>
                    <a href="{{ route('portal.support') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                        Client Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main id="main-content">
        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold uppercase tracking-wide mb-6 animate-fade-in-up">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span> New v2.0 Release
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 max-w-4xl mx-auto">
                    Support ticketing that <br class="hidden sm:block" />
                    <span class="gradient-text">actually works</span>
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-600 mb-10">
                    Streamline your IT support with Stickets. The all-in-one platform for monitoring, ticketing, and remote management.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('portal.support') }}" class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30 ring-offset-2 focus:ring-2 focus:ring-indigo-500">
                        Access Support Portal
                    </a>
                    <a href="#features" class="px-8 py-4 rounded-xl bg-white text-slate-700 border border-slate-200 font-bold text-lg hover:bg-slate-50 transition hover:border-slate-300">
                        Learn More
                    </a>
                </div>
                
                <!-- Hero Image / Dashboard Preview -->
                <div class="mt-16 relative mx-auto max-w-5xl">
                    <div class="rounded-2xl bg-slate-900/5 p-2 ring-1 ring-inset ring-slate-900/10 lg:-m-4 lg:rounded-3xl lg:p-4">
                        <div class="rounded-xl bg-white shadow-2xl ring-1 ring-slate-900/10 overflow-hidden">
                            <!-- Placeholder for Dashboard Screenshot -->
                            <div class="bg-slate-50 aspect-[16/9] flex items-center justify-center text-slate-400">
                                <div class="text-center">
                                    <svg class="w-20 h-20 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span class="text-lg font-medium">Interactive Dashboard Preview</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Background Decorations -->
            <div class="absolute top-0 inset-x-0 h-full -z-10 overflow-hidden" aria-hidden="true">
                <div class="absolute left-[calc(50%-11rem)] top-[calc(50%-30rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Powerful Features</h2>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900 sm:text-4xl">Everything you need to manage IT support</p>
                    <p class="mt-4 text-xl text-slate-500">From ticket tracking to remote desktop control, Stickets provides a complete suite of tools for modern IT teams.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-6 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Ticketing</h3>
                        <p class="text-slate-500 leading-relaxed">Automated ticket routing, SLA management, and custom workflows to keep your team efficient.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
                        <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-6 text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Asset Monitoring</h3>
                        <p class="text-slate-500 leading-relaxed">Real-time health checks for all devices. Monitor CPU, RAM, and disk usage instantly.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Secure Remote Access</h3>
                        <p class="text-slate-500 leading-relaxed">Integrated remote desktop capabilities allowing instant support sessions with end-to-end encryption.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-slate-900 mb-16">Trusted by innovative teams</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-slate-50 p-8 rounded-2xl relative">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"Stickets revolutionized how we handle internal support requests. The minimal portal is a game changer for our employees."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-300 flex items-center justify-center text-xs font-bold">JS</div>
                            <div>
                                <h4 class="font-bold text-slate-900">John Smith</h4>
                                <p class="text-xs text-slate-500">CTO, TechCorp</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-slate-50 p-8 rounded-2xl relative">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"Deployment was instant. The agent installed on our fleet of 50 laptops in minutes. Highly recommended."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">MD</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Maria Davis</h4>
                                <p class="text-xs text-slate-500">Ops Manager, StartUp Inc</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-slate-50 p-8 rounded-2xl relative md:col-span-2 lg:col-span-1">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"The support team is incredible. Any issues are resolved within minutes. Stickets is a partner, not just a tool."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-xs font-bold text-pink-600">AL</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Alex Lee</h4>
                                <p class="text-xs text-slate-500">Director, Creative Agency</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-24 bg-slate-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-slate-900 mb-16">Frequently Asked Questions</h2>
                
                <div class="space-y-6" x-data="{ active: null }">
                    <!-- FAQ 1 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-900">How do I reset my password?</span>
                            <span class="text-slate-400" x-text="active === 1 ? '−' : '+'"></span>
                        </button>
                        <div x-show="active === 1" x-collapse class="px-6 pb-4 text-slate-600">
                            You can reset your password from the login screen by clicking "Forgot Password". A reset link will be sent to your email.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-900">Is there a free trial?</span>
                            <span class="text-slate-400" x-text="active === 2 ? '−' : '+'"></span>
                        </button>
                        <div x-show="active === 2" x-collapse class="px-6 pb-4 text-slate-600">
                            Yes! We offer a 14-day free trial with full access to all features. No credit card required.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-900">Can I access Stickets from mobile?</span>
                            <span class="text-slate-400" x-text="active === 3 ? '−' : '+'"></span>
                        </button>
                        <div x-show="active === 3" x-collapse class="px-6 pb-4 text-slate-600">
                            Absolutely. Stickets is fully responsive and works great on all mobile devices and tablets.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-indigo-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-extrabold text-white mb-6">Ready to transform your IT support?</h2>
                <p class="text-indigo-100 text-xl mb-10">Join thousands of companies using Stickets to power their internal helpdesk.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="px-8 py-4 rounded-xl bg-white text-indigo-600 font-bold text-lg hover:bg-indigo-50 transition shadow-lg">
                        Get Started for Free
                    </a>
                    <a href="#" class="px-8 py-4 rounded-xl bg-indigo-700 text-white border border-indigo-500 font-bold text-lg hover:bg-indigo-800 transition">
                        Contact Sales
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-1">
                    <span class="text-2xl font-bold text-white mb-4 block">stickets</span>
                    <p class="text-sm">Making IT support simple, efficient, and human.</p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Product</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">Roadmap</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('portal.support') }}" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">API Docs</a></li>
                        <li><a href="#" class="hover:text-white transition">Status</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm">© {{ date('Y') }} Stickets Inc. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition"><span class="sr-only">Twitter</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg></a>
                    <a href="#" class="hover:text-white transition"><span class="sr-only">GitHub</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
