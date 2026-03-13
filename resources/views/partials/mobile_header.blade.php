<header class="sticky top-0 z-[45] glass-card px-5 py-3.5 flex lg:hidden items-center justify-between border-b border-gray-100/50 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/silarang-logo.png') }}" class="h-6 w-6 rounded-md" alt="Logo">
            <h1 class="text-xs font-black tracking-tight text-gray-800 uppercase tracking-widest">SI-LARANG</h1>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <!-- Theme Toggle Mobile -->
        <button @click="theme = (theme === 'dark' ? 'light' : 'dark')"
            class="btn-icon-mini bg-gray-50 transition-all duration-300"
            :class="theme === 'dark' ? 'text-yellow-400' : 'text-gray-400'">
            <i class="fas" :class="theme === 'dark' ? 'fa-sun' : 'fa-moon'"></i>
        </button>

        <!-- Notification Trigger Mobile -->
        <button @click="notifOpen = true" class="btn-icon-mini bg-gray-50 text-gray-400 relative">
            <i class="fas fa-bell text-xs"></i>
            @if (isset($lowStockCount) && $lowStockCount > 0)
                <span class="absolute top-2 right-2.5 flex h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            @endif
        </button>

        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 overflow-hidden shadow-sm">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}" class="w-full h-full object-cover">
        </div>
    </div>
</header>
