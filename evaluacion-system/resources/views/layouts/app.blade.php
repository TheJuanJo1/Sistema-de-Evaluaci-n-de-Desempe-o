<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Evaluación de Desempeño') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Outfit', sans-serif; margin: 0; padding: 0; background-color: #f8fafc; }
            
            /* Sidebar Base Styles */
            .main-layout {
                display: flex;
                height: 100vh;
                width: 100vw;
                overflow: hidden;
            }

            .sidebar-custom {
                height: 100%;
                background-color: #334155; /* Medium Slate Blue */
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                flex-shrink: 0;
                z-index: 50;
                display: flex;
                flex-direction: column;
                position: relative;
            }

            .content-wrapper {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
                height: 100%;
                overflow: hidden;
            }

            .main-content {
                flex: 1;
                overflow-y: auto;
                padding: 2rem;
            }

            .sidebar-collapsed { width: 80px; }
            .sidebar-expanded { width: 280px; }

            /* Mobile Sidebar Overlay */
            @media (max-width: 1024px) {
                .sidebar-custom {
                    position: fixed;
                    left: 0;
                    top: 0;
                    width: 280px;
                    transform: translateX(-100%);
                }
                .sidebar-open { transform: translateX(0); }
                .sidebar-collapsed { width: 280px; } /* Don't collapse on mobile */
            }

            .sidebar-link-active {
                background: rgba(59, 130, 246, 0.1);
                color: #60a5fa !important;
                border-right: 4px solid #3b82f6;
            }
        </style>
    </head>
    <body class="antialiased text-slate-900">
        <div class="main-layout" x-data="{ sidebarOpen: false, sidebarCollapsed: true }">
            
            <!-- Sidebar -->
            <div 
                class="sidebar-custom"
                :class="{ 
                    'sidebar-collapsed': sidebarCollapsed, 
                    'sidebar-expanded': !sidebarCollapsed,
                    'sidebar-open': sidebarOpen 
                }"
                @mouseenter="sidebarCollapsed = false"
                @mouseleave="sidebarCollapsed = true"
            >
                @include('layouts.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="content-wrapper">
                
                <!-- Navbar -->
                @include('layouts.navigation')

                <!-- Main Content -->
                <main class="main-content">
                    @isset($header)
                        <div class="mb-10">
                            {{ $header }}
                        </div>
                    @endisset

                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>

                <footer class="bg-white border-t border-slate-200 py-4 px-10 text-slate-400 text-[10px] uppercase font-bold tracking-widest flex justify-between items-center shrink-0">
                    <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
                    <span>v1.0.5</span>
                </footer>
            </div>

            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
                 x-cloak>
            </div>
        </div>

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>
