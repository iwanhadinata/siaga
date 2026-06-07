<?php $uri = uri_string(); ?>

<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden" x-cloak></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:shrink-0 lg:h-screen shadow-2xl lg:shadow-none">

    <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 shrink-0">
        <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shrink-0 shadow-sm shadow-blue-200">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <div>
            <p class="text-[13px] font-bold text-slate-800 leading-tight">SIAGA</p>
            <p class="text-[10px] text-slate-400">Administrasi Gereja</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto p-3 space-y-1 custom-scrollbar">

        <span class="block text-[10px] font-bold tracking-widest uppercase text-slate-400 px-3 mt-4 mb-1.5">Utama</span>
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors <?= $uri === 'dashboard' ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <?php $isMasterActive = str_starts_with($uri, 'jemaat') || str_starts_with($uri, 'pelayanan') || str_starts_with($uri, 'komisi'); ?>
        <span class="block text-[10px] font-bold tracking-widest uppercase text-slate-400 px-3 mt-4 mb-1.5">Master Data</span>

        <div x-data="{ openMaster: <?= $isMasterActive ? 'true' : 'false' ?> }">
            <button @click="openMaster = !openMaster" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors <?= $isMasterActive ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Jemaat
                </div>
                <svg :class="openMaster ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openMaster" x-collapse>
                <div class="mt-1 space-y-1">
                    <a href="<?= base_url('jemaat') ?>" class="block px-4 py-2 pl-10 text-[12px] font-medium rounded-lg <?= str_starts_with($uri, 'jemaat') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' ?>">
                        &bull; Setup Data Jemaat
                    </a>

                    <a href="<?= base_url('jemaat/search') ?>" class="block px-4 py-2 pl-10 text-[12px] font-medium rounded-lg <?= str_starts_with($uri, 'jemaat/search') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' ?>">
                        &bull; Pencarian Lanjutan
                    </a>

                    <a href="<?= base_url('pelayanan') ?>" class="block px-4 py-2 pl-10 text-[12px] font-medium rounded-lg <?= str_starts_with($uri, 'pelayanan') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' ?>">
                        &bull; Setup Data Pelayanan
                    </a>
                    <a href="<?= base_url('komisi') ?>" class="block px-4 py-2 pl-10 text-[12px] font-medium rounded-lg <?= str_starts_with($uri, 'komisi') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' ?>">
                        &bull; Komisi / Sektor
                    </a>
                </div>
            </div>
        </div>

        <?php $isAcaraActive = str_starts_with($uri, 'kebaktian') || str_starts_with($uri, 'presensi'); ?>
        <span class="block text-[10px] font-bold tracking-widest uppercase text-slate-400 px-3 mt-4 mb-1.5">Operasional</span>

        <a href="<?= base_url('kebaktian') ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors <?= str_starts_with($uri, 'kebaktian') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Jadwal Kebaktian
        </a>

        <a href="<?= base_url('presensi') ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors <?= str_starts_with($uri, 'presensi') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Presensi & Kehadiran
        </a>

        <span class="block text-[10px] font-bold tracking-widest uppercase text-slate-400 px-3 mt-4 mb-1.5">Sistem</span>
        <a href="<?= base_url('pengaturan') ?>" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors <?= str_starts_with($uri, 'pengaturan') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Pengaturan Aplikasi
        </a>

    </nav>

    <div class="border-t border-slate-100 p-3 shrink-0">
        <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-3 py-2 text-[13px] font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar Aplikasi
        </a>
    </div>
</aside>