<header class="bg-white/80 backdrop-blur-md border-b border-slate-200 h-20 flex items-center justify-between px-6 md:px-10 sticky top-0 z-30 shadow-sm">
    
    <!-- Left Side: Toggle and Title -->
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl text-slate-500 hover:bg-slate-100 transition duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="flex flex-col">
            <h1 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em] hidden md:block">Sistema de Gestión</h1>
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hidden md:block">Institucional</span>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center space-x-3">
        
        <!-- Notifications -->
        @livewire('notification-bell')

        <div class="h-8 w-px bg-slate-200 mx-2"></div>

        <!-- User Dropdown -->
        <x-dropdown align="right" width="60">
            <x-slot name="trigger">
                <button class="flex items-center space-x-3 p-1 rounded-2xl hover:bg-slate-50 transition duration-200 focus:outline-none border border-transparent hover:border-slate-200">
                    <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-200">
                        <span class="font-black text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden lg:flex flex-col items-start text-left">
                        <span class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ Auth::user()->getRoleNames()->first() }}</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Sesión Iniciada</p>
                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="p-2">
                    <x-dropdown-link :href="route('profile.edit')" class="rounded-lg flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Mi Perfil
                    </x-dropdown-link>
    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="rounded-lg flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Cerrar Sesión
                        </x-dropdown-link>
                    </form>
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</header>
