<!-- Sidebar Canvas for Interactive Particles -->
<canvas id="sidebar-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; opacity: 0.8; pointer-events: none;"></canvas>

<!-- Logo Section -->
<div class="flex items-center justify-center h-28 border-b border-white/20 bg-black/40 shrink-0 relative z-10">
    <div class="flex items-center transition-all duration-300" :class="sidebarCollapsed ? 'px-0' : 'px-6 space-x-4 w-full'">
        <div class="flex-shrink-0 flex items-center justify-center" style="width: 52px; height: 52px;">
            <img src="{{ asset('img/R.png') }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Logo">
        </div>
        <div x-show="!sidebarCollapsed" x-transition.opacity class="flex flex-col min-w-0">
            <span class="text-xs font-black text-white uppercase tracking-widest truncate">Bethlemitas</span>
            <span class="text-[10px] text-blue-300 font-bold uppercase tracking-tight truncate">Evaluacion</span>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="flex-1 py-8 space-y-4 overflow-y-auto overflow-x-hidden custom-scrollbar relative z-10">
    
    <div class="px-6 mb-4 flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
        <p x-show="!sidebarCollapsed" class="text-[10px] font-black text-white uppercase tracking-[0.25em]">Principal</p>
        <div x-show="sidebarCollapsed" class="w-4 h-px bg-white/40"></div>
    </div>

    <a href="{{ route('dashboard') }}" 
       class="flex items-center px-6 py-4 transition-all duration-200 hover:bg-white/10 group {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}"
       title="Dashboard">
        <div class="flex items-center w-8 justify-center shrink-0">
            <svg class="w-6 h-6 text-white transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <span x-show="!sidebarCollapsed" class="ml-5 font-bold text-sm tracking-tight truncate text-white">Dashboard</span>
    </a>

    @role('Administrador|Rector|Coord. Académico|Coord. Convivencia|Talento Humano')
    <div class="pt-6">
        <div class="px-6 mb-4 flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
            <p x-show="!sidebarCollapsed" class="text-[10px] font-black text-white uppercase tracking-[0.25em]">Evaluaciones</p>
            <div x-show="sidebarCollapsed" class="w-4 h-px bg-white/40"></div>
        </div>
        
        <a href="{{ route('evaluations.index') }}" 
           class="flex items-center px-6 py-4 transition-all duration-200 hover:bg-white/10 group {{ request()->routeIs('evaluations.*') ? 'sidebar-link-active' : '' }}"
           title="Gestionar Evaluaciones">
            <div class="flex items-center w-8 justify-center shrink-0">
                <svg class="w-6 h-6 text-white transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
            </div>
            <span x-show="!sidebarCollapsed" class="ml-5 font-bold text-sm tracking-tight truncate text-white">Evaluaciones</span>
        </a>
    </div>
    @endrole

    @role('Administrador|Talento Humano')
    <div class="pt-6">
        <div class="px-6 mb-4 flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
            <p x-show="!sidebarCollapsed" class="text-[10px] font-black text-white uppercase tracking-[0.25em]">Gestión</p>
            <div x-show="sidebarCollapsed" class="w-4 h-px bg-white/40"></div>
        </div>

        <a href="{{ route('workers.index') }}" 
           class="flex items-center px-6 py-4 transition-all duration-200 hover:bg-white/10 group {{ request()->routeIs('workers.*') ? 'sidebar-link-active' : '' }}"
           title="Trabajadores">
            <div class="flex items-center w-8 justify-center shrink-0">
                <svg class="w-6 h-6 text-white transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <span x-show="!sidebarCollapsed" class="ml-5 font-bold text-sm tracking-tight truncate text-white">Trabajadores</span>
        </a>

        @role('Administrador')
        <a href="{{ route('users.index') }}" 
           class="flex items-center px-6 py-4 transition-all duration-200 hover:bg-white/10 group {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}"
           title="Usuarios">
            <div class="flex items-center w-8 justify-center shrink-0">
                <svg class="w-6 h-6 text-white transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <span x-show="!sidebarCollapsed" class="ml-5 font-bold text-sm tracking-tight truncate text-white">Usuarios</span>
        </a>
        @endrole

        @role('Administrador')
        <a href="{{ route('periods.index') }}" 
           class="flex items-center px-6 py-4 transition-all duration-200 hover:bg-white/10 group {{ request()->routeIs('periods.*') ? 'sidebar-link-active' : '' }}"
           title="Periodos">
            <div class="flex items-center w-8 justify-center shrink-0">
                <svg class="w-6 h-6 text-white transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span x-show="!sidebarCollapsed" class="ml-5 font-bold text-sm tracking-tight truncate text-white">Periodos</span>
        </a>
        @endrole
    </div>
    @endrole
</nav>

<!-- User Profile Area -->
<div class="p-6 border-t border-white/20 bg-black/40 shrink-0 relative z-10">
    <div class="flex items-center transition-all duration-300" :class="sidebarCollapsed ? 'justify-center' : 'space-x-4'">
        <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-900/50 flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div x-show="!sidebarCollapsed" class="flex-1 min-w-0">
            @php 
                $userName = auth()->user()->name;
                $userRole = auth()->user()->getRoleNames()->first();
            @endphp
            <p class="text-sm font-black text-white truncate">{{ $userName }}</p>
            @if(strtolower($userName) !== strtolower($userRole))
                <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest truncate">{{ $userRole }}</p>
            @endif
        </div>
    </div>
</div>

<script>
    (function() {
        const canvas = document.getElementById('sidebar-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let particles = [];
        let mouse = { x: -1000, y: -1000 };

        document.querySelector('.sidebar-custom').addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        });

        document.querySelector('.sidebar-custom').addEventListener('mouseleave', () => {
            mouse.x = -1000;
            mouse.y = -1000;
        });

        function resize() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.reset();
            }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 1.5 + 0.5; // Back to elegant smaller circles
                this.speedX = Math.random() * 0.4 - 0.2;
                this.speedY = Math.random() * 0.4 - 0.2;
                this.color = 'rgba(255, 255, 255, ' + (Math.random() * 0.6 + 0.3) + ')';
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;

                const dx = mouse.x - this.x;
                const dy = mouse.y - this.y;
                const distance = Math.sqrt(dx*dx + dy*dy);
                if (distance < 80) {
                    const force = (80 - distance) / 80;
                    this.x -= (dx / distance) * force * 5;
                    this.y -= (dy / distance) * force * 5;
                }
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < 120; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }
        animate();
    })();
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    .sidebar-link-active { background: rgba(255, 255, 255, 0.15) !important; }
    .sidebar-link-active span { color: #fff !important; }
    .sidebar-link-active svg { color: #60a5fa !important; }
</style>
