<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Inventory') }}</title>
  <link rel="icon" type="image/png" href="{{ asset('images/simpatis.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">

  @vite(['resources/css/desktop.css', 'resources/js/desktop.js'])
  
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body class="font-sans antialiased text-slate-800 bg-[#f8faff] min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-[1000px] bg-white rounded-[3rem] shadow-[0_40px_100px_-20px_rgba(79,70,229,0.15)] overflow-hidden flex flex-col lg:flex-row min-h-[650px] border border-white animate__animated animate__zoomIn">
    
    <!-- Left Column: Visual Showcase -->
    <div class="lg:w-1/2 bg-[#1e60d5] relative overflow-hidden hidden lg:flex flex-col p-12 text-white">
      <!-- Decorative Patterns -->
      <div class="absolute inset-0 opacity-10">
        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
          <circle cx="0" cy="0" r="40" fill="white" />
          <circle cx="100" cy="100" r="40" fill="white" />
        </svg>
      </div>
      
      <div class="relative z-10 flex flex-col h-full">
        <a href="/" class="inline-flex items-center gap-2 font-black text-xl tracking-tighter hover:opacity-80 transition">
          <img src="{{ asset('images/simpatis.png') }}" class="h-8 w-8 brightness-0 invert" alt="Logo">
          SIMPATI
        </a>

        <div class="mt-auto mb-auto">
          <div class="relative group">
            <div class="absolute -inset-4 bg-white/20 rounded-[2rem] blur-xl group-hover:bg-white/30 transition duration-500"></div>
            <div class="relative bg-white/10 backdrop-blur-md border border-white/20 rounded-[2rem] p-4 overflow-hidden shadow-2xl">
              <img src="{{ asset('images/login-bg-neww.png') }}" class="w-full h-auto rounded-[1.5rem] transform group-hover:scale-105 transition duration-700" alt="Dashboard Preview" loading="lazy">
            </div>
          </div>
          
          <div class="mt-12 space-y-4">
            <h2 class="text-3xl font-black leading-tight">Pengelolaan Aset <br> Jadi Lebih Mudah</h2>
            <p class="text-white/70 font-medium text-sm max-w-sm">Optimalkan inventarisasi dan pelaporan barang daerah dengan sistem informasi yang terintegrasi dan aman.</p>
          </div>
        </div>

        <!-- <div class="mt-auto flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-white/50">
          <span>&copy; 2026 Bolsel IT Team</span>
          <span class="w-1 h-1 bg-white/30 rounded-full"></span>
          <span>Version 2.0</span>
        </div>
      </div> -->

      <!-- Wave divider (SVG) -->
      <div class="absolute top-0 bottom-0 -right-px w-20 pointer-events-none">
        <svg class="h-full w-full" viewBox="0 0 100 1000" preserveAspectRatio="none">
          <path d="M0,0 C50,200 0,400 50,600 C100,800 50,1000 0,1000 L100,1000 L100,0 Z" fill="white" />
        </svg>
      </div>
    </div>

    <!-- Right Column: Form -->
    <div class="w-full lg:w-1/2 p-8 sm:p-16 flex flex-col justify-center bg-white">
      <div class="w-full max-w-sm mx-auto">
        {{ $slot }}
      </div>
    </div>

  </div>

</body>
</html>
