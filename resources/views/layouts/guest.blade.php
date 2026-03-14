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

<body class="font-sans antialiased text-slate-900 bg-slate-50 flex min-h-screen overflow-hidden">
    <!-- Left Side - Image/Illustration (Desktop Only) -->
    <div class="hidden lg:flex w-1/2 relative bg-indigo-600 overflow-hidden items-center justify-center">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-700 via-indigo-600 to-purple-600 z-0"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 z-10 mix-blend-overlay"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-white/10 rounded-full mix-blend-screen filter blur-[100px] animate-blob"></div>
        <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[45%] h-[45%] bg-indigo-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob animation-delay-4000"></div>

        <!-- Main Image Container -->
        <div class="relative z-20 w-full h-full p-12 flex items-center justify-center animate__animated animate__fadeIn">
            <div class="relative w-full max-w-sm aspect-square rounded-[3rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)] border border-white/20 group">
                <div class="absolute inset-0 bg-indigo-500/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                <img src="{{ asset('images/login-bg-new.jpg') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-1000 ease-in-out">
                
                <!-- Floating Badge -->
                <div class="absolute bottom-8 right-8 bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl shadow-xl animate-float z-20">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-indigo-500 rounded-xl text-white">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white font-black text-sm uppercase tracking-tight">Secure System</p>
                            <p class="text-white/70 text-[10px] uppercase font-bold tracking-widest">Data Encrypted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Overlay -->
        <div class="absolute bottom-12 left-12 z-30">
            <h2 class="text-4xl font-black text-white tracking-widest uppercase mb-2">SI-LARANG</h2>
            <div class="h-1 w-12 bg-white/40 mb-3"></div>
            <p class="text-indigo-100/90 font-bold text-sm max-w-xs uppercase tracking-tight">Sistem Informasi Pengelolaan Persediaan Barang Daerah.</p>
        </div>
    </div>

    <!-- Right Side - Auth Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 relative bg-slate-50 overflow-y-auto">
        <!-- Background Pattern (Mobile & Desktop) -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] z-0 pointer-events-none"></div>
        
        <!-- Animated Blobs for Light Theme (Desktop) -->
        <div class="absolute top-[10%] -right-[10%] w-[40%] h-[40%] bg-indigo-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob lg:block hidden"></div>
        <div class="absolute -bottom-[10%] left-[10%] w-[40%] h-[40%] bg-purple-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-2000 lg:block hidden"></div>

        <div class="w-full max-w-[440px] relative z-10 animate__animated animate__fadeIn">
            
            <!-- Logo Header (Centering for both Mobile/Desktop) -->
            <div class="text-center mb-8 flex flex-col items-center">
                <div class="inline-flex items-center justify-center mb-4 p-3 bg-white rounded-2xl shadow-sm border border-slate-100 transform -rotate-3 transition hover:rotate-0">
                    <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
                </div>
                <div class="flex flex-col items-center">
                    <h2 class="text-2xl font-black text-slate-800 tracking-[0.2em] uppercase leading-none">SI-LARANG</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">Inventory Management System</p>
                </div>
            </div>

            <!-- Premium Card Form -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_-15px_rgba(79,70,229,0.1)] border border-slate-100 p-8 sm:p-12 relative overflow-hidden">
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center animate__animated animate__fadeIn animate__delay-1s">
                <p class="text-[9px] text-slate-300 font-black tracking-[0.3em] uppercase leading-loose">
                    &copy; 2026 Emon Alentadu <br>
                    <span class="text-slate-200">Versi 4.2.0 - Stabil</span>
                </p>
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
        
        @media (max-width: 640px) {
            body { overflow-y: auto; overflow-x: hidden; align-items: stretch; }
            body > div.w-full.lg\:w-1\/2 { min-height: 100vh; padding: 1.5rem !important; align-items: center !important; }
            .bg-white.rounded-\[2\.5rem\] { padding: 1.5rem !important; border-radius: 2rem !important; }
        }
    </style>
</body>
</html>

</html>
