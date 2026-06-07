<?php

/**
 * Dideklarasikan untuk PHP Intelephense agar mengenali variabel dari Controller
 * @var \App\Entities\Jemaat $jemaat
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<div x-data="{ activeTab: 'umum' }" class="max-w-6xl mx-auto">

  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-slate-800">Detail Profil Jemaat</h2>
      <p class="text-sm text-slate-500">Menampilkan data lengkap: <?= esc($jemaat->nama_lengkap) ?></p>
    </div>
    <a href="<?= base_url('jemaat/search') ?>" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-lg hover:bg-slate-50 transition-colors">
      &larr; Kembali ke Pencarian
    </a>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="flex overflow-x-auto border-b border-slate-200 custom-scrollbar bg-slate-50/50">
      <?php
      $tabs = [
        ['id' => 'umum', 'name' => '1. Umum', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['id' => 'keluarga', 'name' => '2. Keluarga', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
      ];
      ?>
      <template x-for="tab in <?= htmlspecialchars(json_encode($tabs)) ?>" :key="tab.id">
        <button type="button" @click="activeTab = tab.id"
          :class="activeTab === tab.id ? 'border-blue-600 text-blue-700 bg-blue-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
          class="flex items-center gap-2 px-5 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors outline-none cursor-pointer">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" :d="tab.icon" />
          </svg>
          <span x-text="tab.name"></span>
        </button>
      </template>
    </div>

    <div class="p-6 md:p-8">
      <div x-show="activeTab === 'umum'" x-transition.opacity>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-8">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Induk Jemaat (NIJ)</label>
            <input type="text" value="<?= esc($jemaat->nij) ?>" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input type="text" value="<?= esc($jemaat->nama_lengkap) ?>" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin</label>
            <input type="text" value="<?= $jemaat->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?>" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat, Tanggal Lahir</label>
            <input type="text" value="<?= esc($jemaat->tempat_lahir) ?>, <?= esc($jemaat->tanggal_lahir) ?>" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Jemaat</label>
            <span class="inline-flex px-3 py-1.5 rounded-md text-xs font-bold <?= $jemaat->status_jemaat === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-700' ?>">
              <?= esc($jemaat->status_jemaat) ?>
            </span>
          </div>
        </div>

        <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 text-base">Alamat & Kontak</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
          <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap</label>
            <textarea readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed"><?= esc($jemaat->alamat) ?> RT <?= esc($jemaat->rt) ?>/RW <?= esc($jemaat->rw) ?>, <?= esc($jemaat->kelurahan) ?></textarea>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">No HP</label>
            <input type="text" value="<?= esc($jemaat->hp1) ?>" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-600 cursor-not-allowed">
          </div>
        </div>
      </div>

      <div x-show="activeTab === 'keluarga'" x-transition.opacity style="display: none;">
        <div class="py-12 text-center text-slate-500">
          Data keluarga untuk <?= esc($jemaat->nama_lengkap) ?> ditampilkan di sini.
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>