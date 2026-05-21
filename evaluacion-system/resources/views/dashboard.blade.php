<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Panel de Control</h2>
                <p class="text-slate-500 font-medium mt-1">Bienvenido, {{ auth()->user()->name }}. Revisa el resumen de hoy.</p>
            </div>
            <div class="hidden md:flex items-center space-x-2 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm">
                <span class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-200">{{ now()->format('d M, Y') }}</span>
            </div>
        </div>
    </x-slot>

    @php
        $activePeriod = \App\Models\EvaluationPeriod::active()->first();
        $totalWorkers = \App\Models\Worker::where('is_active', true)->count();
        $completedEvaluations = $activePeriod ? \App\Models\Evaluation::where('period_id', $activePeriod->id)->where('status', 'Completada')->count() : 0;
        $pendingEvaluations = $activePeriod ? \App\Models\Evaluation::where('period_id', $activePeriod->id)->where('status', '!=', 'Completada')->count() : 0;
        $totalEvaluations = $completedEvaluations + $pendingEvaluations;
        $progressPercent = $totalEvaluations > 0 ? round(($completedEvaluations / $totalEvaluations) * 100) : 0;
    @endphp

    <div class="space-y-10">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total Workers Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-300"></div>
                <div class="relative bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Personal</span>
                    </div>
                    <div>
                        <p class="text-4xl font-black text-slate-900 tracking-tight">{{ $totalWorkers }}</p>
                        <p class="text-slate-500 font-bold text-sm mt-1">Trabajadores Activos</p>
                    </div>
                </div>
            </div>

            <!-- Active Period Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-300"></div>
                <div class="relative bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Periodo</span>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-900 tracking-tight truncate">{{ $activePeriod->name ?? 'Sin Periodo' }}</p>
                        <p class="text-slate-500 font-bold text-sm mt-1">Fase de Evaluación</p>
                    </div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="relative group lg:col-span-2">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-300"></div>
                <div class="relative bg-white p-8 rounded-3xl shadow-sm border border-slate-100 h-full flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Progreso Global</h3>
                        <span class="text-2xl font-black text-emerald-600">{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-1000" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <div class="flex justify-between mt-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <span>{{ $completedEvaluations }} Completadas</span>
                        <span>{{ $pendingEvaluations }} Pendientes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
            <!-- Quick Actions -->
            <div class="xl:col-span-2 space-y-8">
                <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-black mb-4 leading-tight">Gestiona el Desempeño <br>de tu Institución</h3>
                        <p class="text-slate-400 text-lg mb-10 max-w-lg font-medium">Inicia las evaluaciones para el personal docente y administrativo. Recuerda que el seguimiento continuo garantiza la excelencia educativa.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('evaluations.index') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-700 transition duration-300 shadow-xl shadow-blue-900/50">
                                Iniciar Evaluación
                                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <a href="{{ route('workers.index') }}" class="inline-flex items-center px-8 py-4 bg-white/10 hover:bg-white/20 rounded-2xl font-black text-sm uppercase tracking-widest backdrop-blur-md transition duration-300">
                                Ver Personal
                            </a>
                        </div>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-500/20 rounded-full blur-[100px]"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[60px]"></div>
                </div>
            </div>

            <!-- Status Sidecard -->
            <div class="space-y-8">
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-800 mb-8 border-b border-slate-100 pb-4">Servicios del Sistema</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-slate-600 font-bold text-sm">Servidor Base de Datos</span>
                            </div>
                            <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg">ONLINE</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-slate-600 font-bold text-sm">Módulo de Firmas</span>
                            </div>
                            <span class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">ACTIVO</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-slate-600 font-bold text-sm">Reportes PDF (DomPDF)</span>
                            </div>
                            <span class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">LISTO</span>
                        </div>
                    </div>
                </div>

                <div x-data="{ showSupportModal: false }">
                    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-8 text-white shadow-xl">
                        <p class="text-indigo-100 text-xs font-black uppercase tracking-[0.2em] mb-2">Ayuda y Soporte</p>
                        <h4 class="text-xl font-bold mb-4">¿Necesitas ayuda con el sistema?</h4>
                        <p class="text-indigo-100/70 text-sm mb-6">Envía un mensaje directamente a Talento Humano para solicitar soporte técnico o solicitar ajustes.</p>
                        <button @click="showSupportModal = true" class="w-full block text-center py-3 bg-white text-indigo-700 rounded-xl font-black text-sm transition hover:bg-indigo-50">Contactar Soporte</button>
                    </div>

                    <!-- Modal de Soporte -->
                    <div x-show="showSupportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            
                            <div x-show="showSupportModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showSupportModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                            
                            <div x-show="showSupportModal" 
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl">
                                
                                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                                    <div>
                                        <h3 class="text-2xl font-black text-slate-800">Enviar Solicitud de Soporte</h3>
                                        <p class="text-sm text-slate-500 font-medium">El mensaje será enviado a Talento Humano.</p>
                                    </div>
                                    <button @click="showSupportModal = false" class="text-slate-400 hover:text-red-500 transition duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <form action="{{ route('support.send') }}" method="POST">
                                    @csrf
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Tu Nombre</label>
                                            <input type="text" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" disabled class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-500 cursor-not-allowed">
                                        </div>
                                        
                                        <div>
                                            <label for="subject" class="block text-sm font-bold text-slate-700 mb-2">Asunto <span class="text-red-500">*</span></label>
                                            <input type="text" id="subject" name="subject" required placeholder="Ej: Problema con contraseña, Cambio de Rol..." class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl px-4 py-3 text-slate-700 transition duration-200">
                                        </div>

                                        <div>
                                            <label for="message" class="block text-sm font-bold text-slate-700 mb-2">Mensaje <span class="text-red-500">*</span></label>
                                            <textarea id="message" name="message" rows="4" required placeholder="Describe tu problema o consulta detalladamente..." class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl px-4 py-3 text-slate-700 transition duration-200 resize-none"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8 flex justify-end space-x-3">
                                        <button type="button" @click="showSupportModal = false" class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition duration-200">Cancelar</button>
                                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition duration-200 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            Enviar Mensaje
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
