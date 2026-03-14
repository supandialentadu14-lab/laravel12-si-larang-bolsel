@props(['title' => null, 'subtitle' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'SI-LARANG') }}</title>

  {{-- Typography --}}
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  {{-- Icons & Animations --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Alpine JS --}}
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
    body { -webkit-tap-highlight-color: transparent; }
    .mesh-gradient {
      background-color: #ffffff;
      background-image: 
        radial-gradient(at 0% 0%, hsla(230,100%,97%,1) 0, transparent 50%), 
        radial-gradient(at 50% 0%, hsla(240,100%,98%,1) 0, transparent 50%), 
        radial-gradient(at 100% 0%, hsla(260,100%,97%,1) 0, transparent 50%),
        radial-gradient(at 0% 100%, hsla(230,100%,97%,1) 0, transparent 50%),
        radial-gradient(at 50% 100%, hsla(240,100%,98%,1) 0, transparent 50%),
        radial-gradient(at 100% 100%, hsla(260,100%,97%,1) 0, transparent 50%);
    }
  </style>
</head>
<body class="mesh-gradient text-slate-800 font-sans antialiased h-full overflow-hidden flex flex-col items-center justify-center p-2 sm:p-4">

  {{-- Main Container --}}
  <div class="w-full max-w-[360px] sm:max-w-md animate__animated animate__zoomIn animate__faster flex flex-col items-center">
    
    {{-- Header Section --}}
    <div class="text-center mb-4 sm:mb-6">
      <div class="inline-flex p-2 sm:p-3 bg-white rounded-[1.2rem] sm:rounded-[1.5rem] shadow-lg shadow-indigo-100/50 border border-slate-50 mb-2 sm:mb-3 transition-transform hover:scale-105 duration-500">
        <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo" class="h-8 w-8 sm:h-10 sm:w-10 object-contain" onerror="this.src='https://ui-avatars.com/api/?name=S&color=4F46E5&background=EEF2FF'">
      </div>
      <h1 class="text-lg sm:text-xl font-black text-slate-800 tracking-[0.2em] uppercase leading-none">SI-LARANG</h1>
      <p class="text-[6px] sm:text-[7px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1.5 sm:mt-2">Sistem Informasi Pengelolaan Persediaan Barang</p>
    </div>

    {{-- Auth Card --}}
    <div class="w-full bg-indigo-600 rounded-[2.5rem] p-[2px] shadow-[0_24px_48px_-12px_rgba(79,70,229,0.25)]">
      <div class="bg-white rounded-[2.4rem] p-5 sm:p-10 relative overflow-hidden">
        {{-- Decorative element --}}
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-50 rounded-full blur-2xl opacity-50"></div>
        
        <div class="relative z-10">
          @if($title)
          <div class="mb-4 sm:mb-6 text-center">
            <h2 class="text-lg sm:text-xl font-black text-slate-800 tracking-tight uppercase">{{ $title }}</h2>
            @if($subtitle)
            <p class="text-[7px] sm:text-[8px] font-bold text-slate-400 mt-0.5 sm:mt-1 uppercase tracking-widest">{{ $subtitle }}</p>
            @endif
          </div>
          @endif

          <div class="max-h-[60vh] overflow-y-auto no-scrollbar">
            {{ $slot }}
          </div>
        </div>
      </div>
    </div>

    {{-- Social & Footer --}}
    <div class="mt-4 sm:mt-6 flex flex-col items-center gap-2 sm:gap-4">
      <div class="text-[7px] sm:text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em]">More info</div>
      {{-- Social Links --}}
      <div class="flex items-center gap-4 sm:gap-6 text-slate-400">
        <a href="https://wa.me/6285824268216" target="_blank" class="hover:text-green-500 transition-colors duration-300">
          <i class="fab fa-whatsapp text-base sm:text-lg"></i>
        </a>
        <a href="https://www.instagram.com/emonn_65" target="_blank" class="hover:text-rose-500 transition-colors duration-300">
          <i class="fab fa-instagram text-base sm:text-lg"></i>
        </a>
        <a href="https://www.facebook.com/share/18J61xd2XQ" target="_blank" class="hover:text-blue-600 transition-colors duration-300">
          <i class="fab fa-facebook text-base sm:text-lg"></i>
        </a>
      </div>

      {{-- Copyright --}}
      <p class="text-[7px] sm:text-[8px] font-bold text-slate-300 tracking-[0.2em] uppercase text-center">
        &copy; 2026 Emon Alentadu &bull;
      </p>
    </div>
  </div>

</body>
</html>
