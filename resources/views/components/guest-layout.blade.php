@props(['title' => null, 'subtitle' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SI-LARANG') }}</title>
    
    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Icons & Animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        body { 
            height: 100vh; 
            height: 100dvh; 
            overflow: hidden; 
            position: relative; 
            background: #f0f4ff;
        }
        
        .mesh-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: 
                radial-gradient(at 0% 0%, hsla(230,100%,93%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(240,100%,95%,1) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(260,100%,94%,1) 0, transparent 50%),
                radial-gradient(at 0% 100%, hsla(230,100%,93%,1) 0, transparent 50%);
        }
        
        @media (max-width: 1024px) {
            .auth-card {
                height: 95vh;
                height: 95dvh;
                max-height: 700px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 flex items-center justify-center p-3 sm:p-6">
    <div class="mesh-bg"></div>

    <div class="auth-card w-full max-w-[850px] bg-white/80 backdrop-blur-2xl rounded-[2.5rem] lg:rounded-[3rem] shadow-[0_40px_100px_-20px_rgba(79,70,229,0.15)] overflow-hidden flex flex-col lg:flex-row h-full max-h-[620px] border border-white/50 animate__animated animate__zoomIn">
        
        <!-- Branding Section (Desktop: Left | Mobile: Top) -->
        <div class="w-full lg:w-[45%] bg-[#1e60d5] relative overflow-hidden flex flex-col p-4 px-6 lg:p-8 text-white min-h-[85px] lg:min-h-0">
            <!-- Decorative Patterns -->
            <div class="absolute inset-0 opacity-10">
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <circle cx="0" cy="0" r="40" fill="white" />
                    <circle cx="100" cy="100" r="40" fill="white" />
                </svg>
            </div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center lg:block">
                    <a href="/" class="flex items-center gap-2 lg:gap-3 group">
                        <img src="{{ asset('images/silarang-logo.webp') }}" class="h-6 w-6 lg:h-7 lg:w-7 object-contain drop-shadow-lg" alt="Logo">
                        <span class="font-black text-sm lg:text-lg tracking-tighter">SI-LARANG</span>
                    </a>
                </div>

                <div class="mt-auto mb-auto lg:block hidden">
                    <div class="relative group">
                        <div class="absolute -inset-3 bg-white/20 rounded-[1.8rem] blur-lg group-hover:bg-white/30 transition duration-500"></div>
                        <div class="relative bg-white/10 backdrop-blur-md border border-white/20 rounded-[1.8rem] p-2.5 overflow-hidden shadow-2xl">
                            <img src="{{ asset('images/login-bg-neww.png') }}" class="w-full h-auto rounded-[1.2rem] transform group-hover:scale-105 transition duration-700" alt="Dashboard Preview">
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <h2 class="text-xl font-black leading-tight">Pengelolaan Persediaan Barang <br> Jadi Lebih Mudah</h2>
                        <p class="text-white/70 font-medium text-[10px] max-w-[250px]">Optimalkan inventarisasi dan pelaporan barang daerah dengan Sistem Informasi Pengelolaan Persediaan Barang.</p>
                    </div>
                </div>

                <!-- Simple Mobile Branding Text -->
                <div class="mt-2 lg:hidden">
                    <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-60">Sistem Informasi Pengelolaan Persediaan Barang</p>
                </div>

                <div class="mt-auto hidden lg:flex items-center gap-4 text-[8px] font-black uppercase tracking-widest text-white/50">
                    <span>&copy; 2026 Emon Alentadu</span>
                </div>
            </div>

            <!-- Wave divider (Desktop only) -->
            <div class="absolute top-0 bottom-0 -right-px w-16 pointer-events-none hidden lg:block">
                <svg class="h-full w-full" viewBox="0 0 100 1000" preserveAspectRatio="none">
                    <path d="M0,0 C50,200 0,400 50,600 C100,800 50,1000 0,1000 L100,1000 L100,0 Z" fill="white" />
                </svg>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-[55%] p-4 lg:p-8 flex flex-col bg-white lg:bg-white/50 relative overflow-hidden flex-1">
            
            <div class="flex-1 flex flex-col overflow-hidden">
                <div class="w-full max-w-[320px] mx-auto overflow-y-auto no-scrollbar py-2 px-1">
                    @if($title)
                    <div class="mb-3 text-center lg:text-left">
                        <h2 class="text-2xl lg:text-3xl font-black text-slate-900 mb-1 uppercase tracking-tight leading-none">{{ $title }}</h2>
                        @if($subtitle)
                        <p class="text-slate-400 font-medium text-[9px] lg:text-[11px] mt-1.5 uppercase tracking-widest leading-relaxed">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @endif

                    <div class="pb-2">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="mt-1 text-center space-y-1.5 border-t border-slate-100 pt-3 pb-1">
                    @if(Request::routeIs('login'))
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                            Belum punya akun? <a href="{{ route('register') }}" class="font-black text-indigo-600 hover:text-indigo-800 ml-1">Daftar</a>
                        </p>
                    @elseif(Request::routeIs('register'))
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                            Sudah punya akun? <a href="{{ route('login') }}" class="font-black text-indigo-600 hover:text-indigo-800 ml-1">Login</a>
                        </p>
                    @endif
                    <div>
                        <a href="/" class="text-[8px] font-black text-slate-300 hover:text-indigo-600 uppercase tracking-widest transition-colors flex items-center justify-center gap-1">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Social Footer -->
            <div class="mt-2 pt-2 border-t border-slate-50 flex justify-center gap-8 text-slate-300">
                <a href="https://wa.me/6285824268216" target="_blank" class="hover:text-green-500 transition-all transform hover:scale-110"><i class="fab fa-whatsapp text-lg"></i></a>
                <a href="https://instagram.com/emonn_65" target="_blank" class="hover:text-rose-500 transition-all transform hover:scale-110"><i class="fab fa-instagram text-lg"></i></a>
                <a href="https://facebook.com/share/18J61xd2XQ" target="_blank" class="hover:text-blue-600 transition-all transform hover:scale-110"><i class="fab fa-facebook text-lg"></i></a>
            </div>
        </div>
    </div>
</body>
</html>
