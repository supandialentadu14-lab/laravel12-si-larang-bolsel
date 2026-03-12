<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Inventory') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/silarang-logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
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
        .bg-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#4F46E5 0.5px, transparent 0.5px), radial-gradient(#4F46E5 0.5px, #ffffff 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
        .form-input:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .login-illustration {
            mask-image: linear-gradient(to right, black 85%, transparent 100%);
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-900 flex min-h-screen overflow-hidden">
    <!-- Left Side - Image/Illustration -->
    <div class="hidden lg:flex w-1/2 relative bg-gray-900 overflow-hidden items-center justify-center">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-900/90 via-gray-900/40 to-purple-900/90 z-0"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 z-10 mix-blend-overlay"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-brand-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[45%] h-[45%] bg-pink-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-4000"></div>

        <!-- Main Image Container -->
        <div class="relative z-20 w-full h-full p-12 flex items-center justify-center animate__animated animate__fadeIn">
            <div class="relative w-full max-w-sm aspect-square rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 group">
                <div class="absolute inset-0 bg-brand-500/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                <img src="{{ asset('images/login-bg-new.jpg') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-1000 ease-in-out">
                
                <!-- Floating Badge -->
                <div class="absolute bottom-6 right-6 bg-white/10 backdrop-blur-md border border-white/20 p-3 rounded-xl shadow-xl animate-float z-20">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 bg-green-500/20 rounded-lg">
                            <i class="fas fa-shield-alt text-green-400 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-xs">Secure System</p>
                            <p class="text-white/60 text-[10px]">Data Encrypted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Overlay -->
        <div class="absolute bottom-8 left-8 z-30">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-white tracking-tight drop-shadow-sm mb-1">SI-LARANG</h2>
            <p class="text-blue-100/80 font-medium text-sm max-w-xs">Sistem Informasi Pengelolaan Persediaan Barang Daerah.</p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-start sm:items-center justify-center p-4 sm:p-6 relative bg-gray-900 overflow-y-auto">
        <!-- Background Image for Mobile Only -->
        <div class="absolute inset-0 lg:hidden block z-0">
            <img src="{{ asset('images/login-bg-new.jpg') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
        </div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 z-0"></div>
        <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-bl from-brand-900/20 to-transparent z-0 lg:block hidden"></div>
        <div class="absolute top-0 right-0 w-full h-full bg-brand-900/40 z-0 lg:hidden block"></div>
        
        <div class="w-full max-w-md relative z-10 animate__animated animate__fadeInRight">
            
            <!-- Logo Mobile Header -->
            <div class="text-center lg:hidden mb-2 flex flex-col items-center pt-2">
                <div class="inline-flex items-center justify-center mb-1 p-1 bg-white/5 backdrop-blur-md rounded-lg border border-white/10">
                    <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo" class="h-6 w-6 object-contain">
                </div>
                <div class="flex flex-col items-center">
                    <h2 class="text-xs font-black text-white tracking-[0.3em] uppercase leading-none">SI-LARANG</h2>
                    <div class="h-[1px] w-6 bg-brand-500 my-1"></div>
                    <p class="text-[7px] text-white font-bold leading-tight uppercase tracking-tight">Sistem Informasi Pengelolaan Persediaan barang</p>
                </div>
            </div>

            <!-- Glass Card Form -->
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.36)] border border-white/10 p-4 sm:p-10 relative overflow-hidden group">
                <!-- Shine Effect -->
                <div class="absolute inset-0 -translate-x-full group-hover:animate-[shine_1.5s_infinite] bg-gradient-to-r from-transparent via-white/5 to-transparent z-0 pointer-events-none"></div>
                
                <div class="relative z-10">
                    {{ $slot }}

                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 sm:mt-6 text-center animate__animated animate__fadeIn animate__delay-1s">
                <p class="text-[8px] text-white/30 font-medium tracking-widest uppercase">Copyright © 2026 Emon Alentadu</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes shine { 100% { left: 125%; } }

        /* ══ Guest Layout Responsive ══ */

        /* Mobile: 360px - 640px */
        @media (max-width: 640px) {
            /* Allow vertical scroll on short mobile devices */
            body { overflow-y: auto; overflow-x: hidden; align-items: flex-start; }

            /* Right side scrolls on mobile */
            body > div.w-full.lg\:w-1\/2 {
                min-height: 100svh;
                padding: 1rem !important;
                align-items: flex-start !important;
                padding-top: 1.5rem !important;
            }

            /* Form card more compact on mobile */
            .bg-white\/5.backdrop-blur-xl.rounded-2xl {
                padding: 1rem !important;
                border-radius: 1rem !important;
            }

            /* Social media icons smaller on mobile */
            .flex.items-center.justify-center.gap-4 a {
                width: 2.25rem !important;
                height: 2.25rem !important;
            }

            /* Text adjustments */
            h1 { font-size: 1.375rem !important; }
        }

        /* Tablet: 641px - 1023px */
        @media (min-width: 641px) and (max-width: 1023px) {
            body { overflow-y: auto; }
            body > div.w-full.lg\:w-1\/2 {
                padding: 2rem;
                min-height: 100svh;
            }
        }

        /* Short viewport fix */
        @media (max-height: 700px) {
            body { overflow-y: auto; align-items: flex-start; }
        }
    </style>
</body>
</html>
