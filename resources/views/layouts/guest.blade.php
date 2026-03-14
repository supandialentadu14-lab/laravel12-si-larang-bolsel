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

<body class="font-sans antialiased text-slate-800 bg-white flex min-h-screen overflow-hidden">
    <!-- Left Side - Illustration (Desktop Only) -->
    <div class="hidden lg:flex w-1/2 relative bg-slate-50 overflow-hidden items-center justify-center">
        <!-- Abstract Background -->
        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-50 via-white to-purple-50 z-0"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] z-10"></div>
        
        <!-- Subtle Blobs -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-indigo-100/50 rounded-full filter blur-[100px] animate-blob"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-purple-100/50 rounded-full filter blur-[100px] animate-blob animation-delay-2000"></div>

        <!-- Main Image Container -->
        <div class="relative z-20 w-full h-full p-16 flex items-center justify-center animate__animated animate__fadeIn">
            <div class="relative w-full max-w-sm aspect-square rounded-[3rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(79,70,229,0.15)] border border-white/80 group bg-white">
                <div class="absolute inset-0 bg-indigo-50/20 group-hover:bg-transparent transition duration-500 z-10"></div>
                <img src="{{ asset('images/login-bg-new.jpg') }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-1000 ease-in-out opacity-90 group-hover:opacity-100">
                
                <!-- Floating Badge -->
                <div class="absolute bottom-8 right-8 bg-white/80 backdrop-blur-md border border-white p-4 rounded-2xl shadow-xl animate-float z-20">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-100">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-800 font-black text-sm uppercase tracking-tight">Terverifikasi</p>
                            <p class="text-slate-400 text-[9px] uppercase font-bold tracking-widest">Sistem Aman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Overlay -->
        <div class="absolute bottom-12 left-12 z-30">
            <h2 class="text-4xl font-black text-slate-800 tracking-widest uppercase mb-2">SI-LARANG</h2>
            <div class="h-1 w-12 bg-indigo-600/20 mb-3"></div>
            <p class="text-slate-500 font-bold text-sm max-w-xs uppercase tracking-tight leading-relaxed">Sistem Informasi Pengelolaan Persediaan Barang Daerah.</p>
        </div>
    </div>

    <!-- Right Side - Auth Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 relative bg-white overflow-y-auto">
        <!-- Background Decor -->
        <div class="absolute top-[5%] -right-[5%] w-[30%] h-[30%] bg-indigo-50 rounded-full filter blur-[60px] opacity-60 animate-blob"></div>
        <div class="absolute bottom-[5%] left-[5%] w-[30%] h-[30%] bg-purple-50 rounded-full filter blur-[60px] opacity-60 animate-blob animation-delay-2000"></div>

        <div class="w-full max-w-[420px] relative z-10 animate__animated animate__fadeIn">
            
            <!-- Logo Header -->
            <div class="text-center mb-10 flex flex-col items-center">
                <div class="inline-flex items-center justify-center mb-5 p-4 bg-white rounded-3xl shadow-[0_10px_30px_-5px_rgba(79,70,229,0.1)] border border-slate-50 transform -rotate-3 transition hover:rotate-0">
                    <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                </div>
                <div class="flex flex-col items-center">
                    <h2 class="text-2xl font-black text-slate-800 tracking-[0.2em] uppercase leading-none">SI-LARANG</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3">Inventory Management System</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-[3rem] border border-slate-100 p-8 sm:p-12 shadow-[0_20px_60px_-15px_rgba(79,70,229,0.06)] relative transition-all hover:shadow-[0_25px_70px_-15px_rgba(79,70,229,0.08)]">
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-10 text-center animate__animated animate__fadeIn animate__delay-1s px-6">
                <p class="text-[9px] text-slate-300 font-black tracking-[0.3em] uppercase leading-loose">
                    &copy; 2026 Emon Alentadu <br>
                    <span class="text-indigo-200">Sistem Persediaan Terintegrasi</span>
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
        .animate-blob { animation: blob 10s infinite; }
        .animation-delay-2000 { animation-delay: 3s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @media (max-width: 640px) {
            body { overflow-y: auto; overflow-x: hidden; }
            body > div.w-full.lg\:w-1\/2 { min-height: 100vh; padding: 1.5rem !important; }
            .bg-white.rounded-\[3rem\] { padding: 2rem 1.5rem !important; border-radius: 2.5rem !important; }
        }
    </style>
</body>

</html>

</html>
