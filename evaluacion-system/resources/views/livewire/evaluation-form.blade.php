<div class="space-y-10">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-2xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-bold text-blue-800">Instrucciones de Evaluación</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Como <strong>{{ $userRole }}</strong>, estás evaluando las competencias asignadas a tu rol para este trabajador ({{ $evaluation->worker->type }}). 
                    Por favor, asigna un puntaje del 1 al 5 donde 1 es "Nunca" y 5 es "Siempre". Es obligatorio dejar una observación detallada al final.
                </p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-2xl mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-red-800">Error al guardar</h3>
                    <p class="text-sm text-red-700 mt-1">
                        Por favor revisa que hayas respondido todas tus preguntas asignadas y que la observación cumpla con el mínimo de caracteres.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-12">
        @foreach($competencies as $competency)
            <section wire:key="comp-{{ $competency->id }}" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden transition duration-300 hover:shadow-md">
                <div class="bg-slate-800 px-8 py-5 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white tracking-tight">{{ $competency->name }}</h3>
                    <span class="px-3 py-1 bg-white/10 text-white/80 text-xs font-bold rounded-full uppercase tracking-widest border border-white/20">Competencia</span>
                </div>
                
                <div class="p-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="text-left py-4 text-xs font-bold text-slate-400 uppercase tracking-widest w-2/3">Criterio de Evaluación</th>
                                <th class="text-center py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Escala (1 - 5)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($competency->questions as $question)
                                <tr wire:key="question-{{ $question->id }}" class="group hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-6 pr-8">
                                        <p class="text-slate-700 font-medium leading-relaxed">{{ $question->text }}</p>
                                        @error("answers.{$question->id}") <span class="text-xs text-red-500 font-bold mt-1 block">Debe seleccionar una opción</span> @enderror
                                    </td>
                                    <td class="py-6">
                                        <div class="flex items-center justify-center space-x-2 md:space-x-4">
                                            @php
                                                $canEvaluate = $isAdmin || $competency->evaluator_role === $userRole;
                                            @endphp
                                            @foreach(range(1, 5) as $val)
                                                <div wire:key="q-{{ $question->id }}-v-{{ $val }}" class="relative flex flex-col items-center group/radio">
                                                    <label class="relative flex flex-col items-center {{ $canEvaluate ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }} group/radio">
                                                        <input type="radio" 
                                                               @if($canEvaluate) wire:click="selectAnswer({{ $question->id }}, {{ $val }}, '{{ $competency->evaluator_role }}')" @endif
                                                               @checked(isset($answers[$question->id]) && $answers[$question->id] == $val)
                                                               @disabled(!$canEvaluate)
                                                               class="sr-only peer"
                                                               name="q-{{ $question->id }}">
                                                        <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-xl border-2 border-slate-200 bg-white text-slate-400 font-bold text-lg peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white transition-all duration-200 shadow-sm {{ $canEvaluate ? 'group-hover/radio:border-blue-200' : '' }}">
                                                            {{ $val }}
                                                        </div>
                                                        <span class="text-[10px] mt-1 font-bold uppercase peer-checked:text-blue-600 text-slate-400">
                                                            @if($val == 1) Nunca @elseif($val == 5) Siempre @endif
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

        <!-- Observation Section -->
        <section class="bg-slate-50 rounded-3xl p-8 border border-slate-200">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">Observaciones Generales</h3>
                <p class="text-sm text-slate-500">Deja un comentario detallado sobre el desempeño del trabajador en esta área.</p>
            </div>
            
            <textarea wire:model="observation" rows="5" class="block w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-2xl shadow-sm text-slate-700 placeholder-slate-400 transition duration-200" placeholder="Escribe aquí tus observaciones..."></textarea>
            @error('observation') <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p> @enderror
        </section>

        @if($evaluation->worker->type === 'Docente')
        <!-- Satisfaction Survey Section -->
        <section class="bg-amber-50 rounded-3xl p-8 border border-amber-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-xl">
                    <h3 class="text-xl font-bold text-amber-900">Satisfacción del Servicio (15%)</h3>
                    <p class="text-sm text-amber-700 mt-1">Ingresa el promedio obtenido en la encuesta de satisfacción de estudiantes y otras fuentes externas (escala 1 a 5).</p>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-amber-800 mb-2 uppercase tracking-widest text-center">Puntaje (1-5)</label>
                    <input type="number" step="0.01" min="1" max="5" wire:model="satisfactionScore" class="block w-full border-amber-200 focus:border-amber-500 focus:ring-amber-500 rounded-2xl shadow-sm text-center text-2xl font-bold text-amber-900 py-4">
                </div>
            </div>
        </section>
        @endif

        <!-- Improvement Plan Section -->
        <section class="bg-blue-50/50 rounded-3xl p-8 border border-blue-100">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-blue-900">Plan de Mejora Institucional</h3>
                <p class="text-sm text-blue-600">Define los aspectos a fortalecer y el compromiso del trabajador.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-blue-800 mb-2 uppercase tracking-widest">Aspectos por Mejorar</label>
                    <textarea wire:model="aspectsToImprove" rows="4" class="block w-full border-blue-100 focus:border-blue-500 focus:ring-blue-500 rounded-2xl shadow-sm text-slate-700 placeholder-slate-400" placeholder="¿Qué debe mejorar el trabajador?"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-blue-800 mb-2 uppercase tracking-widest">Compromiso de Mejora (Trabajador)</label>
                    <textarea wire:model="workerCommitment" rows="4" class="block w-full border-blue-100 focus:border-blue-500 focus:ring-blue-500 rounded-2xl shadow-sm text-slate-700 placeholder-slate-400" placeholder="¿A qué se compromete el trabajador?"></textarea>
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end space-x-4 pt-4">
            <a href="{{ route('evaluations.index') }}" class="px-8 py-3 text-slate-600 font-bold hover:text-slate-800 transition duration-200">
                Cancelar
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold text-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200 shadow-xl shadow-blue-200 uppercase tracking-widest">
                Finalizar Evaluación
            </button>
        </div>
    </form>
</div>
