<header class="sticky top-0 z-[45] glass-card px-5 py-3.5 flex lg:hidden items-center justify-between border-b border-gray-100/50 dark:border-slate-800/50 shadow-sm transition-colors duration-300">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/silarang-logo.png') }}" class="h-6 w-6 rounded-md" alt="Logo">
            <h1 class="text-xs font-black tracking-tight text-gray-800 dark:text-gray-100 uppercase tracking-widest transition-colors">SI-LARANG</h1>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <!-- Theme Toggle Mobile -->
        <button @click="toggleDarkMode()"
            class="btn-icon-mini bg-gray-50 dark:bg-slate-800 transition-all duration-300"
            :class="darkMode ? 'text-yellow-400' : 'text-slate-400'">
            <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon-stars'"></i>
        </button>

        <!-- Notification Trigger Mobile -->
        <button @click="notifOpen = true" class="btn-icon-mini bg-gray-50 dark:bg-slate-800 text-gray-400 dark:text-slate-500 relative transition-colors">
            <i class="fas fa-bell text-xs"></i>
            @if (isset($lowStockCount) && $lowStockCount > 0)
                <span class="absolute top-2 right-2.5 flex h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-slate-900"></span>
            @endif
        </button>

        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-800 border border-indigo-100 dark:border-slate-700 overflow-hidden shadow-sm transition-colors">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}" class="w-full h-full object-cover">
        </div>
    </div>
</header>
