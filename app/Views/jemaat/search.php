<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/**
 * Dideklarasikan untuk PHP Intelephense agar bebas error
 * @var \App\Entities\Jemaat|null $jemaat
 */

$req = service('request');

// Closure cerdas: Mengambil data dari database (jika ada), atau dari parameter pencarian (GET)
$v = function (string $key) use ($req, $jemaat): string {
  if ($jemaat && isset($jemaat->$key)) {
    return (string) $jemaat->$key;
  }
  $val = $req->getGet($key);
  return is_string($val) ? $val : '';
};
?>

<div x-data="jemaatSearch()" class="max-w-6xl mx-auto">

  <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-slate-800">Pencarian Data Jemaat</h2>
      <p class="text-sm text-slate-500">Ketik nama atau NIJ pada kolom di bawah. Klik hasil untuk mengisi seluruh tab secara otomatis.</p>
    </div>

    <?php if ($jemaat): ?>
      <a href="<?= base_url('jemaat/search') ?>" class="px-4 py-2 bg-blue-50 text-blue-700 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors">
        &times; Bersihkan Form (Pencarian Baru)
      </a>
    <?php endif; ?>
  </div>

  <div class="mb-8 relative z-50">
    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <input type="text"
        x-model="searchQuery"
        @input.debounce.500ms="fetchAutocomplete()"
        @click.away="showDropdown = false"
        @focus="if(searchResults.length > 0) showDropdown = true"
        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm"
        placeholder="Pencarian Cepat: Ketik Nama, NIJ, atau No. HP (Min. 3 karakter)..." autocomplete="off">

      <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-4 flex items-center" style="display: none;">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <div x-show="showDropdown && searchResults.length > 0" x-transition.opacity class="absolute mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden" style="display: none;">
      <ul class="max-h-64 overflow-y-auto custom-scrollbar divide-y divide-slate-100">
        <template x-for="item in searchResults" :key="item.id">
          <li>
            <a :href="`<?= base_url('jemaat/search') ?>?id=${item.id}`" class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors">
              <div class="h-10 w-10 rounded-full bg-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                <svg class="h-6 w-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-bold text-slate-800" x-text="item.nama_lengkap"></p>
                <p class="text-xs text-slate-500" x-text="`NIJ: ${item.nij} • Status: ${item.status_jemaat}`"></p>
              </div>
            </a>
          </li>
        </template>
      </ul>
    </div>
  </div>

  <form action="<?= base_url('jemaat/index') ?>" method="GET" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="flex overflow-x-auto border-b border-slate-200 custom-scrollbar bg-slate-50/50">
      <template x-for="tab in tabList" :key="tab.id">
        <button type="button"
          @click="activeTab = tab.id"
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

      <div x-show="activeTab === 'umum'" x-transition.opacity style="display: none;">
        <div class="flex flex-col md:flex-row gap-6 md:gap-8 mb-8 items-start">

          <div class="w-full flex-none mx-auto md:mx-0" style="max-width: 14rem;">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Jemaat</label>
            <div class="relative w-full border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 overflow-hidden flex flex-col items-center justify-center" style="aspect-ratio: 3/4;">
              <?php if ($jemaat && $jemaat->foto_url): ?>
                <img src="<?= base_url('uploads/jemaat/' . esc($jemaat->foto_url)) ?>" class="absolute inset-0 w-full h-full object-cover">
              <?php else: ?>
                <svg class="h-8 w-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-xs text-slate-400">Tidak ada foto</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="flex-1 min-w-0 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Induk Jemaat (NIJ)</label>
                <input type="text" name="nij" value="<?= esc($v('nij')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= esc($v('nama_lengkap')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                  <option value="">-- Pilih --</option>
                  <option value="L" <?= $v('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                  <option value="P" <?= $v('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Panggilan</label>
                <input type="text" name="nama_panggilan" value="<?= esc($v('nama_panggilan')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
              </div>
            </div>
          </div>
        </div>

        <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 text-base">Detail Identitas Lanjutan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Jemaat</label>
            <select name="status_jemaat" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
              <option value="">-- Semua --</option>
              <option value="Aktif" <?= $v('status_jemaat') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
              <option value="Titipan" <?= $v('status_jemaat') === 'Titipan' ? 'selected' : '' ?>>Titipan</option>
              <option value="Pindah" <?= $v('status_jemaat') === 'Pindah' ? 'selected' : '' ?>>Pindah</option>
              <option value="Meninggal" <?= $v('status_jemaat') === 'Meninggal' ? 'selected' : '' ?>>Meninggal</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="<?= esc($v('tempat_lahir')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="<?= esc($v('tanggal_lahir')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Baptis</label>
            <input type="text" name="tempat_baptis" value="<?= esc($v('tempat_baptis')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Baptis</label>
            <input type="date" name="tanggal_baptis" value="<?= esc($v('tanggal_baptis')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Golongan Darah</label>
              <select name="golongan_darah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                <option value="">-</option>
                <option value="A" <?= $v('golongan_darah') === 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= $v('golongan_darah') === 'B' ? 'selected' : '' ?>>B</option>
                <option value="AB" <?= $v('golongan_darah') === 'AB' ? 'selected' : '' ?>>AB</option>
                <option value="O" <?= $v('golongan_darah') === 'O' ? 'selected' : '' ?>>O</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Rhesus</label>
              <select name="rhesus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                <option value="">-</option>
                <option value="Positif" <?= $v('rhesus') === 'Positif' ? 'selected' : '' ?>>Positif</option>
                <option value="Negatif" <?= $v('rhesus') === 'Negatif' ? 'selected' : '' ?>>Negatif</option>
                <option value="Tidak Tahu" <?= $v('rhesus') === 'Tidak Tahu' ? 'selected' : '' ?>>Tidak Tahu</option>
              </select>
            </div>
          </div>
        </div>

        <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 text-base">Kontak & Alamat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Domisili</label>
            <textarea name="alamat" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500"><?= esc($v('alamat')) ?></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">RT</label>
              <input type="text" name="rt" value="<?= esc($v('rt')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">RW</label>
              <input type="text" name="rw" value="<?= esc($v('rw')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kelurahan / Desa</label>
            <input type="text" name="kelurahan" value="<?= esc($v('kelurahan')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan</label>
            <input type="text" name="kecamatan" value="<?= esc($v('kecamatan')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Kabupaten / Kota</label>
              <input type="text" name="kabupaten" value="<?= esc($v('kabupaten')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Pos</label>
              <input type="text" name="kodepos" value="<?= esc($v('kodepos')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">No. HP 1</label>
              <input type="text" name="hp1" value="<?= esc($v('hp1')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">No. HP 2</label>
              <input type="text" name="hp2" value="<?= esc($v('hp2')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 md:col-span-2">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Email 1</label>
              <input type="email" name="email1" value="<?= esc($v('email1')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1.5">Email 2</label>
              <input type="email" name="email2" value="<?= esc($v('email2')) ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
          </div>
        </div>
      </div>

      <div x-show="activeTab !== 'umum'" x-transition.opacity style="display: none;">
        <div class="py-12 text-center text-slate-500">
          Jika data relasi (seperti Pekerjaan/Pelayanan) sudah ditambahkan di database, Anda bisa memanggilnya di sini menggunakan fungsi <b>$v('nama_kolom')</b> layaknya tab Umum.
        </div>
      </div>

    </div>

    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
      <button type="submit" class="w-full md:w-auto px-6 py-2 flex items-center justify-center gap-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md shadow-blue-600/20 transition-all cursor-pointer outline-none">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        Terapkan Sebagai Filter Pencarian
      </button>
    </div>
  </form>
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('jemaatSearch', () => ({
      activeTab: 'umum',
      searchQuery: '',
      searchResults: [],
      isLoading: false,
      showDropdown: false,

      tabList: [{
          id: 'umum',
          name: '1. Umum',
          icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        },
        {
          id: 'keluarga',
          name: '2. Keluarga',
          icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
        },
        {
          id: 'pekerjaan',
          name: '3. Pekerjaan',
          icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
        },
        {
          id: 'pendidikan',
          name: '4. Pendidikan',
          icon: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'
        },
        {
          id: 'pelayanan',
          name: '5. Pelayanan',
          icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
        },
        {
          id: 'lainnya',
          name: '6. Lain-lain',
          icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'
        }
      ],

      async fetchAutocomplete() {
        if (this.searchQuery.length < 3) {
          this.searchResults = [];
          this.showDropdown = false;
          return;
        }

        this.isLoading = true;

        try {
          const response = await fetch(`<?= base_url('jemaat/autocomplete') ?>?q=${encodeURIComponent(this.searchQuery)}`, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (response.ok) {
            this.searchResults = await response.json();
            this.showDropdown = true;
          }
        } catch (error) {
          console.error('Gagal mengambil data autocomplete:', error);
        } finally {
          this.isLoading = false;
        }
      }
    }));
  });
</script>

<?= $this->endSection() ?>