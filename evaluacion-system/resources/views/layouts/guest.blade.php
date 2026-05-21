<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Evaluación de Desempeño') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Outfit', sans-serif;
                margin: 0;
                background: #0f172a; /* Slate 900 */
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            #bg-canvas {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            .input-modern {
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                color: white !important;
                transition: all 0.3s ease;
            }

            .input-modern:focus {
                border-color: #3b82f6 !important;
                background: rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
            }
        </style>
    </head>
    <body class="antialiased">
        <canvas id="bg-canvas"></canvas>

        <div class="min-h-screen w-full flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
            <div class="w-full sm:max-w-md px-6 py-10 sm:px-10 glass-card sm:rounded-3xl relative z-10">
                <div class="flex justify-center mb-8">
                    <a href="/">
                        <img src="{{ asset('img/R.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain drop-shadow-2xl" alt="Logo">
                    </a>
                </div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-white text-[10px] sm:text-xs font-medium uppercase tracking-widest relative z-10 text-center">
                &copy; {{ date('Y') }} Sistema de Evaluación de Desempeño
            </div>
        </div>

        <script>
            const canvas = document.getElementById('bg-canvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            let mouse = { x: null, y: null };

            window.addEventListener('mousemove', (e) => {
                mouse.x = e.x;
                mouse.y = e.y;
            });

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            window.addEventListener('resize', resize);
            resize();

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2 + 1;
                    this.speedX = Math.random() * 1 - 0.5;
                    this.speedY = Math.random() * 1 - 0.5;
                    this.color = 'rgba(59, 130, 246, ' + (Math.random() * 0.3 + 0.1) + ')';
                }

                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;

                    if (this.x > canvas.width) this.x = 0;
                    if (this.x < 0) this.x = canvas.width;
                    if (this.y > canvas.height) this.y = 0;
                    if (this.y < 0) this.y = canvas.height;

                    // Interaction with mouse
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const distance = Math.sqrt(dx*dx + dy*dy);
                    if (distance < 150) {
                        this.x -= dx / 20;
                        this.y -= dy / 20;
                    }
                }

                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            function init() {
                particles = [];
                for (let i = 0; i < 150; i++) {
                    particles.push(new Particle());
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();
                    
                    // Connect lines
                    for (let j = i; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const distance = Math.sqrt(dx*dx + dy*dy);
                        if (distance < 100) {
                            ctx.strokeStyle = 'rgba(59, 130, 246, ' + (0.1 - distance/1000) + ')';
                            ctx.lineWidth = 0.5;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
                requestAnimationFrame(animate);
            }

            init();
            animate();
        </script>
    </body>
</html>
