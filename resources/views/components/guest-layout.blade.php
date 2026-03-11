<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Inventory') }} - Login</title>

    {{-- Import Google Font Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Import Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Swup JS for Smooth Page Transitions --}}
    <script src="https://unpkg.com/swup@4"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#EEF2FF', 100: '#E0E7FF', 200: '#C7D2FE', 300: '#A5B4FC',
                            400: '#818CF8', 500: '#6366F1', 600: '#4F46E5', 700: '#4338CA',
                            800: '#3730A3', 900: '#312E81',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @keyframes shine { 100% { left: 125%; } }
        
        /* Smooth Page Transition */
        .page-transition {
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .animate-slow {
            --animate-duration: 1.2s;
        }

        /* Swup Transitions */
        html.is-changing .swup-transition {
            opacity: 0;
            transform: scale(0.98);
            filter: blur(4px);
        }
        .swup-transition {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
            transform: scale(1);
            filter: blur(0);
        }
    </style>
</head>

@php $isRegister = request()->routeIs('register'); @endphp
<body class="font-sans antialiased text-gray-900 bg-gray-900 overflow-x-hidden">
    <main id="swup" class="flex min-h-screen page-transition swup-transition {{ $isRegister ? 'lg:flex-row-reverse' : 'lg:flex-row' }}">

    <!-- LEFT SIDE (DESKTOP) / RIGHT SIDE (WHEN REGISTER) -->
    <div class="hidden lg:flex w-1/2 relative bg-gray-900 overflow-hidden items-start justify-center border-white/5 page-transition {{ $isRegister ? 'border-l' : 'border-r' }}">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-900/90 via-gray-900/40 to-purple-900/90 z-0"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 z-10 mix-blend-overlay"></div>
        
        <!-- Main Image Container -->
        <div class="relative z-20 w-full h-full p-4 flex items-start justify-center animate__animated animate-slow {{ $isRegister ? 'lg:animate__fadeInRight animate__fadeIn' : 'lg:animate__fadeInLeft animate__fadeIn' }} lg:pt-44">
            <div class="relative w-full max-w-2xl aspect-video rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 group">
                <img src="{{ asset('images/login-bg-new.png') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-1000 ease-in-out">
                <div class="absolute inset-0 bg-brand-900/20 group-hover:bg-transparent transition duration-500"></div>
                
                <!-- Floating Badge -->
                <div class="absolute bottom-2 right-6 bg-white/10 backdrop-blur-md border border-white/20 p-3 rounded-xl shadow-xl animate-float z-20">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 bg-green-500/20 rounded-lg">
                            <i class="fas fa-shield-alt text-green-400 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-xs uppercase tracking-wider">Secure</p>
                            <p class="text-white/60 text-[10px]">Encrypted Data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Overlay (Centered Horizontal at Top) -->
        <div class="absolute top-12 left-0 right-0 text-center z-30 page-transition px-8">
            <h2 class="text-5xl font-black text-white tracking-tighter mb-3 drop-shadow-2xl animate__animated animate__zoomIn">SI-LARANG</h2>
            <p class="text-blue-100/90 font-semibold text-lg max-w-lg mx-auto leading-relaxed drop-shadow-xl animate__animated animate__fadeInUp animate__delay-1s">
                Sistem Informasi Pengelolaan Persediaan Barang Daerah.
            </p>
        </div>

        <!-- Footer (Desktop - Left Side) -->
        <div class="absolute bottom-24 left-0 right-0 text-center z-30 animate__animated animate__fadeIn animate__delay-1s px-8">
            <!-- <p class="text-[10px] text-white/40 font-bold tracking-[0.2em] uppercase mb-1 drop-shadow-lg">SI-LARANG v2.0</p> -->
            <p class="text-[9px] text-white/30 font-medium drop-shadow-lg">Copyright © 2026 Emon Alentadu. Seluruh Hak Cipta Dilindungi.</p>
        </div>
    </div>

    <!-- FORM SIDE (LOGIN/REGISTER) -->
    <div class="w-full lg:w-1/2 flex items-start justify-center p-6 relative bg-gray-900 min-h-screen pt-4 lg:pt-10 page-transition">
        
        <!-- Mobile Background Image -->
        <div class="absolute inset-0 lg:hidden block z-0">
            <img src="{{ asset('images/login-bg-new.jpg') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-brand-900/40 to-gray-900/90"></div>
        </div>

        <!-- Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 z-0"></div>
        
        <div class="w-full max-w-xl relative z-10 animate__animated {{ $isRegister ? 'lg:animate__fadeInLeft animate__fadeIn' : 'lg:animate__fadeInRight animate__fadeIn' }}">
            <!-- Logo Mobile -->
            <div class="text-center lg:hidden mb-4">
                <div class="inline-flex items-center justify-center mb-1 p-2 bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 ring-1 ring-white/30">
                    <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo" class="h-12 w-12 object-contain" onerror="this.style.display='none'">
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight">SI-LARANG</h2>
                <div class="h-1 w-12 bg-brand-500 mx-auto mt-1 rounded-full"></div>
            </div>

            <!-- Glass Card Form -->
            <div class="bg-white/5 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 p-8 sm:p-12 relative overflow-hidden">
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer (Mobile Only) -->
            <div class="mt-4 text-center lg:hidden animate__animated animate__fadeIn animate__delay-1s">
                <!-- <p class="text-[10px] text-white/30 font-bold tracking-[0.2em] uppercase mb-1">SI-LARANG v2.0</p> -->
                <p class="text-[9px] text-white/20 font-medium">Copyright © 2026 Emon Alentadu. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const swup = new Swup({
                animationSelector: '[class*="swup-transition"]',
                containers: ["#swup"],
                cache: true
            });
        });
    </script>
</body>
</html>
