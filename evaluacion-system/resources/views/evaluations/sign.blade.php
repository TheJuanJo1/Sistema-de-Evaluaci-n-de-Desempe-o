<x-app-layout>
    <x-slot name="header">
        <div>
            <nav class="flex mb-4 text-slate-400 text-xs font-semibold uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('evaluations.index') }}" class="hover:text-blue-600 transition duration-200">Evaluaciones</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2 text-slate-600">Firma de Aceptación</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Firma Electrónica del Trabajador</h2>
            <p class="text-slate-500 mt-1">Trabajador: <span class="font-semibold text-slate-700">{{ $evaluation->worker->name }}</span></p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="p-8">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Consentimiento y Firma</h3>
                    <p class="text-slate-500 mt-2">Al firmar este documento, acepto que he sido evaluado y conozco los resultados presentados en el sistema.</p>
                </div>

                <form id="signature-form" action="{{ route('evaluations.store-signature', $evaluation) }}" method="POST">
                    @csrf
                    <input type="hidden" name="signature" id="signature-input">
                    
                    <div class="relative bg-slate-50 border-2 border-slate-200 border-dashed rounded-2xl overflow-hidden">
                        <canvas id="signature-pad" class="w-full h-64 cursor-crosshair"></canvas>
                        <div class="absolute bottom-4 right-4 flex space-x-2">
                            <button type="button" id="clear-btn" class="px-4 py-2 bg-white border border-slate-300 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-50 transition duration-200 uppercase tracking-widest shadow-sm">
                                Limpiar
                            </button>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-center space-x-4">
                        <a href="{{ route('evaluations.index') }}" class="px-6 py-3 text-slate-600 font-bold hover:text-slate-800 transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" id="save-btn" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold text-lg hover:bg-blue-700 transition duration-200 shadow-xl shadow-blue-200 uppercase tracking-widest">
                            Registrar Firma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(30, 41, 59)'
            });

            // Adjust canvas size
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            document.getElementById('clear-btn').addEventListener('click', function () {
                signaturePad.clear();
            });

            document.getElementById('signature-form').addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('Por favor, realiza tu firma antes de guardar.');
                } else {
                    document.getElementById('signature-input').value = signaturePad.toDataURL();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
