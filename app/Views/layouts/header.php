<header class="bg-white border-b border-slate-200 sticky top-0 z-30 h-14 flex items-center px-4 md:px-6 gap-4">
    <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex-1 min-w-0">
        <h1 class="text-sm font-bold text-slate-800 truncate"><?= esc($title ?? 'Dashboard') ?></h1>
        <?php if (!empty($breadcrumb)): ?>
            <p class="text-[11px] text-slate-400 hidden sm:block truncate"><?= esc($breadcrumb) ?></p>
        <?php endif; ?>
    </div>

    <div x-data="{ notifOpen: false }" class="relative shrink-0">
        <button @click="notifOpen = !notifOpen" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors relative">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>

        <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition class="absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-lg z-50 overflow-hidden" x-cloak>
            <div class="bg-slate-50 px-4 py-2 border-b border-slate-100">
                <p class="text-xs font-bold text-slate-700">Notifikasi</p>
            </div>
            <div class="p-6 text-center text-slate-400 text-xs">Belum ada notifikasi</div>
        </div>
    </div>
</header>