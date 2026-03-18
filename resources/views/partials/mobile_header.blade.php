<header class="sticky top-0 z-[45] glass-card px-5 py-3.5 flex lg:hidden items-center justify-between border-b border-gray-100/50 shadow-sm transition-colors duration-300">
  <div class="flex items-center gap-3">
    <div class="flex items-center gap-2">
      <img src="{{ asset('images/silarang-logo.png') }}" class="h-6 w-6 rounded-md" alt="Logo">
      <h1 class="text-xs font-black tracking-tight text-app-main uppercase tracking-widest transition-colors">SI-LARANG</h1>
    </div>
  </div>
  <div class="flex items-center gap-5">
    @if(auth()->check() && (auth()->user()->chat_enabled || auth()->user()->isAdmin()))
    <!-- Chat Trigger -->
    <a href="{{ route('chat.index') }}" class="btn-icon-mini text-gray-400 relative transition-colors">
      <i class="fas fa-comment-dots text-xs"></i>
      <span x-show="unreadChatCount > 0" class="absolute top-2 right-2.5 flex h-2 w-2 rounded-full bg-indigo-500 ring-2 ring-white"></span>
    </a>
    @endif

    <!-- Notification Trigger Mobile -->
    <button @click="notifOpen = true" class="btn-icon-mini text-gray-400 relative transition-colors">
      <i class="fas fa-bell text-xs"></i>
      @if (isset($lowStockCount) && $lowStockCount > 0)
        <span class="absolute top-2 right-2.5 flex h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
      @endif
    </button>

    <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 overflow-hidden shadow-sm transition-colors">
      <img id="top-profile-img-mobile" src="{{ Auth::user()->avatar ? asset('media/' . Auth::user()->avatar . '?v=' . (Auth::user()->avatar_updated_at?->timestamp ?? time())) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}" class="w-full h-full object-cover">
    </div>
  </div>
</header>
