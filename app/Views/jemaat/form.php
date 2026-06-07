<?php

/**
 * @var array $enums
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div x-data="jemaatForm()" class="max-w-6xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Formulir Data Jemaat</h2>
        <p class="text-sm text-slate-500">Lengkapi informasi detail jemaat pada tab yang tersedia.</p>
    </div>

    <?php if (session()->has('errors')) : ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
            Pastikan semua field yang wajib sudah terisi dengan benar.
        </div>
    <?php endif; ?>

    <form action="<?= base_url('jemaat/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

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

                <!-- TAB Umum -->
                <div x-show="activeTab === 'umum'" x-transition.opacity style="display: none;">
                    <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 text-base">Identitas</h3>
                    <div class="flex flex-col md:flex-row gap-8 mb-8 items-start">
                        <div class="w-full md:w-48 md:min-w-48 shrink-0 flex flex-col">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Jemaat</label>
                            <div @click="$refs.fileInput.click()"
                                class="relative w-full aspect-[3/4] border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden group cursor-pointer flex flex-col items-center justify-center">

                                <input type="file" x-ref="fileInput" name="foto" accept="image/png, image/jpeg, image/jpg"
                                    @change="if($event.target.files.length) photoPreview = URL.createObjectURL($event.target.files[0])"
                                    class="hidden">

                                <div x-show="!photoPreview" class="text-center p-4 flex flex-col items-center justify-center w-full">
                                    <svg class="h-8 w-8 text-slate-400 mb-2 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Pilih Foto</span>
                                </div>

                                <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover" style="display: none;" alt="Preview Foto">

                                <div x-show="photoPreview" class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200" style="display: none;">
                                    <span class="text-white text-xs font-bold px-2.5 py-1.5 bg-slate-900/60 rounded-md backdrop-blur-sm">Ganti Foto</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2 text-center">Format: JPG, PNG. Max: 2MB</p>
                        </div>

                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 w-full">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Induk Jemaat (NIJ)</label>
                                <input type="text" name="nij" value="<?= old('nij') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="Otomatis / Isi manual">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($enums['jenis_kelamin'] as $enum) : ?>
                                        <option value="<?= $enum->value ?>" <?= old('jenis_kelamin') === $enum->value ? 'selected' : '' ?>>
                                            <?= $enum->label() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" value="<?= old('nama_panggilan') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Baptis</label>
                                <input type="text" name="tempat_baptis" value="<?= old('tempat_baptis') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Baptis</label>
                                <input type="date" name="tanggal_baptis" value="<?= old('tanggal_baptis') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>

                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Jemaat</label>
                            <select name="status_jemaat" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">-- Pilih --</option>
                                <?php foreach ($enums['status_jemaat'] as $enum) : ?>
                                    <option value="<?= $enum->value ?>" <?= old('status_jemaat') === $enum->value ? 'selected' : '' ?>>
                                        <?= $enum->label() ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Golongan Darah</label>
                                <select name="golongan_darah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($enums['golongan_darah'] as $enum) : ?>
                                        <option value="<?= $enum->value ?>" <?= old('golongan_darah') === $enum->value ? 'selected' : '' ?>>
                                            <?= $enum->label() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Rhesus</label>
                                <select name="rhesus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($enums['rhesus'] as $enum) : ?>
                                        <option value="<?= $enum->value ?>" <?= old('rhesus') === $enum->value ? 'selected' : '' ?>>
                                            <?= $enum->label() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 text-base">Kontak & Alamat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Domisili</label>
                            <textarea name="alamat" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"><?= old('alamat') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">RT</label>
                                <input type="text" name="rt" value="<?= old('rt') ?>" maxlength="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">RW</label>
                                <input type="text" name="rw" value="<?= old('rw') ?>" maxlength="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" value="<?= old('kelurahan') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan</label>
                            <input type="text" name="kecamatan" value="<?= old('kecamatan') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kabupaten / Kota</label>
                                <input type="text" name="kabupaten" value="<?= old('kabupaten') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Pos</label>
                                <input type="text" name="kodepos" value="<?= old('kodepos') ?>" maxlength="5" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">No. HP 1</label>
                                <input type="text" name="hp1" value="<?= old('hp1') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">No. HP 2</label>
                                <input type="text" name="hp2" value="<?= old('hp2') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email 1</label>
                                <input type="email" name="email1" value="<?= old('email1') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email 2</label>
                                <input type="email" name="email2" value="<?= old('email2') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB Keluarga -->
                <div x-show="activeTab === 'keluarga'" x-transition.opacity style="display: none;">
                    <p class="text-slate-500 text-sm mb-4">Informasi Keluarga (Orang Tua, Pasangan, dan Anak).</p>
                    <div class="space-y-6">

                        <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                            <h3 class="font-bold text-slate-700 text-sm mb-4 border-b border-slate-100 pb-2">Data Orang Tua (Opsional)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                <div class="relative z-40">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Ayah</label>
                                    <input type="hidden" name="orang_tua[ayah_id]" x-model="ayahId">
                                    <div class="relative">
                                        <input type="text" name="orang_tua[nama_ayah]" x-model="ayahNama"
                                            @input.debounce.500ms="searchJemaat('ayah')"
                                            @click.away="showAyahDropdown = false"
                                            autocomplete="off"
                                            placeholder="Cari jemaat (L) atau ketik manual..."
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                        <div x-show="isAyahLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="display: none;">
                                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Tambahkan "Alm." di belakang nama jika sudah meninggal.</p>

                                    <div x-show="showAyahDropdown && ayahResults.length > 0" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                                        <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                                            <template x-for="item in ayahResults" :key="item.id">
                                                <li><button type="button" @click="pilihJemaat('ayah', item)" class="w-full text-left px-4 py-2 hover:bg-slate-50 cursor-pointer"><span class="text-sm font-bold text-slate-800" x-text="item.nama_lengkap"></span></button></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <div class="relative z-30">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Ibu</label>
                                    <input type="hidden" name="orang_tua[ibu_id]" x-model="ibuId">
                                    <div class="relative">
                                        <input type="text" name="orang_tua[nama_ibu]" x-model="ibuNama"
                                            @input.debounce.500ms="searchJemaat('ibu')"
                                            @click.away="showIbuDropdown = false"
                                            autocomplete="off"
                                            placeholder="Cari jemaat (P) atau ketik manual..."
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                        <div x-show="isIbuLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="display: none;">
                                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Tambahkan "Almh." di belakang nama jika sudah meninggal.</p>

                                    <div x-show="showIbuDropdown && ibuResults.length > 0" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                                        <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                                            <template x-for="item in ibuResults" :key="item.id">
                                                <li><button type="button" @click="pilihJemaat('ibu', item)" class="w-full text-left px-4 py-2 hover:bg-slate-50 cursor-pointer"><span class="text-sm font-bold text-slate-800" x-text="item.nama_lengkap"></span></button></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Anda adalah anak ke-</label>
                                    <input type="number" name="orang_tua[urutan_anak]" value="<?= old('orang_tua.urutan_anak') ?>" min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Hubungan Anak</label>
                                    <select name="orang_tua[status_hubungan]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                        <option value="Kandung">Kandung</option>
                                        <option value="Tiri">Tiri</option>
                                        <option value="Angkat">Angkat</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                            <h3 class="font-bold text-slate-700 text-sm mb-4 border-b border-slate-100 pb-2">Status Pernikahan & Data Pasangan</h3>

                            <div class="mb-4 w-full md:w-1/2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Pernikahan Jemaat <span class="text-red-500">*</span></label>
                                <select name="status_pernikahan" x-model="statusPernikahan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">-- Pilih Status Pernikahan --</option>
                                    <?php foreach ($enums['status_pernikahan'] as $enum) : ?>
                                        <option value="<?= $enum->value ?>"><?= $enum->label() ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div x-show="['menikah'].includes(statusPernikahan)" x-collapse>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 pt-4 border-t border-slate-100 mt-2">

                                    <div class="sm:col-span-2 relative z-20">
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pasangan <span class="text-slate-400 font-normal">(Cari dari database atau ketik manual)</span></label>
                                        <input type="hidden" name="pasangan[id_pasangan]" x-model="pasanganId">
                                        <div class="relative">
                                            <input type="text" name="pasangan[nama_pasangan]" x-model="pasanganNama" @input.debounce.500ms="searchJemaat('pasangan')" @click.away="showPasanganDropdown = false" autocomplete="off" placeholder="Ketik nama pasangan..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                            <div x-show="isPasanganLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="display: none;">
                                                <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="mt-1.5 h-5">
                                            <span x-show="pasanganId" class="inline-flex items-center gap-1 text-[11px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded" style="display: none;">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                Terkoneksi dengan Database Jemaat
                                            </span>
                                            <span x-show="!pasanganId && pasanganNama.length > 0" class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded" style="display: none;">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Data Manual (Non-Jemaat / Eksternal)
                                            </span>
                                        </div>

                                        <div x-show="showPasanganDropdown && pasanganResults.length > 0" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                                            <ul class="max-h-48 overflow-y-auto divide-y divide-slate-100">
                                                <template x-for="item in pasanganResults" :key="item.id">
                                                    <li><button type="button" @click="pilihJemaat('pasangan', item)" class="w-full text-left px-4 py-3 hover:bg-slate-50 cursor-pointer"><span class="text-sm font-bold text-slate-800" x-text="item.nama_lengkap"></span></button></li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pekerjaan Pasangan</label>
                                        <input type="text" name="pasangan[pekerjaan_pasangan]" x-model="pasanganPekerjaan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir Pasangan</label>
                                        <input type="text" name="pasangan[tempat_lahir_pasangan]" x-model="pasanganTempatLahir" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir Pasangan</label>
                                        <input type="date" name="pasangan[tanggal_lahir_pasangan]" x-model="pasanganTanggalLahir" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Nikah</label>
                                        <input type="text" name="pasangan[tempat_nikah]" x-model="pasanganTempatNikah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Nikah</label>
                                        <input type="date" name="pasangan[tanggal_nikah]" x-model="pasanganTanggalNikah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                            <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                                <h3 class="font-bold text-slate-700 text-sm">Data Anak</h3>
                                <button type="button" @click="tambahAnak()" class="px-3 py-1.5 bg-green-50 text-green-700 text-xs font-bold rounded-lg hover:bg-green-100 transition-colors cursor-pointer">
                                    + Tambah Anak
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(anak, index) in anakList" :key="index">
                                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg relative">
                                        <button type="button" @click="hapusAnak(index)" class="absolute top-3 right-3 text-red-500 hover:text-red-700 text-xs font-bold bg-white px-2 py-1 rounded shadow-sm border border-red-100 cursor-pointer z-10">
                                            &times; Hapus
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-3">

                                            <div class="relative z-10 lg:col-span-2">
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5" x-text="`Nama Anak ke-${index + 1}`"></label>
                                                <input type="hidden" :name="`anak[${index}][anak_id]`" x-model="anak.anak_id">
                                                <div class="relative">
                                                    <input type="text" :name="`anak[${index}][nama_anak]`" x-model="anak.nama_anak"
                                                        @input.debounce.500ms="searchAnak(index)"
                                                        @click.away="anak.showDropdown = false"
                                                        autocomplete="off"
                                                        placeholder="Cari jemaat atau ketik manual..."
                                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">

                                                    <div x-show="anak.isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="display: none;">
                                                        <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div x-show="anak.showDropdown && anak.results.length > 0" class="absolute mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                                    <ul class="max-h-40 overflow-y-auto divide-y divide-slate-100">
                                                        <template x-for="item in anak.results" :key="item.id">
                                                            <li><button type="button" @click="pilihAnak(index, item)" class="w-full text-left px-4 py-2 hover:bg-slate-50 cursor-pointer text-sm font-bold text-slate-800" x-text="item.nama_lengkap"></button></li>
                                                        </template>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin</label>
                                                <select x-model="anak.jenis_kelamin" :name="`anak[${index}][jenis_kelamin]`" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                                    <option value="">Pilih...</option>
                                                    <?php foreach ($enums['jenis_kelamin'] as $enum) : ?>
                                                        <option value="<?= $enum->value ?>"><?= $enum->label() ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir</label>
                                                <input type="text" x-model="anak.tempat_lahir" :name="`anak[${index}][tempat_lahir]`" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir</label>
                                                <input type="date" x-model="anak.tanggal_lahir" :name="`anak[${index}][tanggal_lahir]`" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pendidikan</label>
                                                <select x-model="anak.pendidikan" :name="`anak[${index}][pendidikan]`" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                                    <option value="">Pilih...</option>
                                                    <?php foreach ($enums['pendidikan'] as $enum) : ?>
                                                        <option value="<?= $enum->value ?>"><?= $enum->label() ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kristen</label>
                                                <select x-model="anak.status_kristen" :name="`anak[${index}][status_kristen]`" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                                    <option value="">Pilih...</option>
                                                    <option value="Ya">Ya</option>
                                                    <option value="Belum">Belum</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB Pekerjaan & Pendidikan -->
                <div x-show="activeTab === 'pekerjaan'" x-transition.opacity style="display: none;">
                    <p class="text-slate-500 text-sm mb-4">Informasi profesi dan detail instansi / tempat kerja.</p>

                    <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm mb-6">
                        <div class="w-full md:w-1/2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Profesi / Pekerjaan Utama <span class="text-red-500">*</span></label>
                            <select name="pekerjaan" x-model="profesiUtama" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">-- Pilih Kategori Pekerjaan --</option>
                                <?php foreach ($enums['profesi'] as $enum) : ?>
                                    <option value="<?= $enum->name ?>"><?= $enum->label() ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div x-show="butuhDetailKantor()" x-collapse>
                        <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                            <h3 class="font-bold text-slate-700 text-sm mb-4 border-b border-slate-100 pb-2">Detail Instansi / Perusahaan</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kantor / Instansi</label>
                                    <input type="text" name="pekerjaan_detail[nama_kantor]" value="<?= old('pekerjaan_detail.nama_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                </div>
                                <div class="sm:col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan / Posisi</label>
                                    <input type="text" name="pekerjaan_detail[jabatan]" value="<?= old('pekerjaan_detail.jabatan') ?>" placeholder="Contoh: Staff IT, Direktur, Guru, dll" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                </div>

                                <div class="sm:col-span-2 pt-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Kantor Lengkap</label>
                                    <textarea name="pekerjaan_detail[alamat_kantor]" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"><?= old('pekerjaan_detail.alamat_kantor') ?></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">RT Kantor</label>
                                        <input type="text" name="pekerjaan_detail[rt_kantor]" value="<?= old('pekerjaan_detail.rt_kantor') ?>" maxlength="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">RW Kantor</label>
                                        <input type="text" name="pekerjaan_detail[rw_kantor]" value="<?= old('pekerjaan_detail.rw_kantor') ?>" maxlength="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kelurahan / Desa</label>
                                    <input type="text" name="pekerjaan_detail[kelurahan_kantor]" value="<?= old('pekerjaan_detail.kelurahan_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan</label>
                                    <input type="text" name="pekerjaan_detail[kecamatan_kantor]" value="<?= old('pekerjaan_detail.kecamatan_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kabupaten / Kota</label>
                                        <input type="text" name="pekerjaan_detail[kabupaten_kantor]" value="<?= old('pekerjaan_detail.kabupaten_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Pos</label>
                                        <input type="text" name="pekerjaan_detail[kodepos_kantor]" value="<?= old('pekerjaan_detail.kodepos_kantor') ?>" maxlength="5" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="sm:col-span-2 pt-2 border-t border-slate-100"></div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">No. Telepon Kantor 1</label>
                                        <input type="text" name="pekerjaan_detail[hp1_kantor]" value="<?= old('pekerjaan_detail.hp1_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">No. Telepon Kantor 2</label>
                                        <input type="text" name="pekerjaan_detail[hp2_kantor]" value="<?= old('pekerjaan_detail.hp2_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Kantor 1</label>
                                        <input type="email" name="pekerjaan_detail[email1_kantor]" value="<?= old('pekerjaan_detail.email1_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Kantor 2</label>
                                        <input type="email" name="pekerjaan_detail[email2_kantor]" value="<?= old('pekerjaan_detail.email2_kantor') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB Pendidikan  -->
                <div x-show="activeTab === 'pendidikan'" x-transition.opacity style="display: none;">
                    <p class="text-slate-500 text-sm mb-4">Informasi riwayat pendidikan terakhir jemaat.</p>

                    <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenjang Pendidikan Terakhir</label>
                                <select name="pendidikan[jenjang_terakhir]" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    <?php foreach ($enums['pendidikan'] as $enum) : ?>
                                        <option value="<?= $enum->value ?>" <?= old('pendidikan.jenjang_terakhir') === $enum->value ? 'selected' : '' ?>>
                                            <?= $enum->label() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Sekolah / Perguruan Tinggi</label>
                                <input type="text" name="pendidikan[nama_institusi]" value="<?= old('pendidikan.nama_institusi') ?>" placeholder="Contoh: SMAN 1 Jakarta / Univ. Indonesia" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jurusan / Program Studi</label>
                                <input type="text" name="pendidikan[jurusan]" value="<?= old('pendidikan.jurusan') ?>" placeholder="Kosongkan jika SD/SMP" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Lulus</label>
                                    <input type="number" name="pendidikan[tahun_lulus]" value="<?= old('pendidikan.tahun_lulus') ?>" min="1900" max="<?= date('Y') + 5 ?>" placeholder="YYYY" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Gelar Akademik</label>
                                    <input type="text" name="pendidikan[gelar]" value="<?= old('pendidikan.gelar') ?>" placeholder="Contoh: S.Kom, M.Th" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TAB Pelayanan -->
                <div x-show="activeTab === 'pelayanan'" x-transition.opacity style="display: none;">
                    <p class="text-slate-500 text-sm mb-4">Pilih satu atau beberapa bidang pelayanan yang diikuti oleh jemaat saat ini.</p>

                    <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">
                        <div class="w-full md:w-2/3 relative">
                            <label class="block text-xs font-bold text-slate-700 mb-2">Bidang Pelayanan Aktif</label>

                            <div @click="showPelayananDropdown = !showPelayananDropdown"
                                class="w-full min-h-10.5 p-2 bg-slate-50 border border-slate-200 rounded-lg text-sm flex flex-wrap gap-2 items-center cursor-pointer focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 transition-all select-none pr-10">

                                <template x-if="selectedPelayanan.length === 0">
                                    <span class="text-slate-400 pl-1">-- Pilih Bidang Pelayanan (Bisa lebih dari satu) --</span>
                                </template>

                                <template x-for="item in selectedPelayanan" :key="item.id">
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md border border-blue-100 transition-all">
                                        <span x-text="item.nama"></span>
                                        <button type="button" @click.stop="hapusPelayanan(item.id)" class="hover:text-red-600 font-black cursor-pointer text-sm leading-none">&times;</button>
                                    </span>
                                </template>

                                <div class="absolute right-3 top-3 text-slate-400 pointer-events-none">
                                    <svg :class="showPelayananDropdown ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <template x-for="item in selectedPelayanan" :key="'input-'+item.id">
                                <input type="hidden" name="pelayanan[]" :value="item.id">
                            </template>

                            <div x-show="showPelayananDropdown"
                                @click.away="showPelayananDropdown = false"
                                x-transition.opacity
                                class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden"
                                style="display: none;">
                                <ul class="max-h-60 overflow-y-auto divide-y divide-slate-100 custom-scrollbar">
                                    <?php if (!empty($enums['pelayanan'])) : ?>
                                        <?php foreach ($enums['pelayanan'] as $pelayanan) : ?>
                                            <li>
                                                <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer w-full text-sm font-medium text-slate-700 select-none">
                                                    <input type="checkbox"
                                                        :checked="isPelayananSelected(<?= (int)$pelayanan['id'] ?>)"
                                                        @change="togglePelayanan(<?= (int)$pelayanan['id'] ?>, '<?= esc($pelayanan['nama_pelayanan']) ?>')"
                                                        class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-slate-300 outline-none cursor-pointer">
                                                    <span class="flex-1 text-slate-700 select-none" x-text="'<?= esc($pelayanan['nama_pelayanan']) ?>'"></span>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <li class="px-4 py-3 text-xs text-slate-400 text-center">Belum ada master data pelayanan.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TAB Lainnya -->
                <div x-show="activeTab === 'lainnya'" x-transition.opacity style="display: none;">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-8 md:p-12 text-center flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-amber-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.014a4.514 4.514 0 011.494 3.156m-7.14-3.128c-.148-.052-.3-.105-.452-.158m0 0L4.318 6.318a4.5 4.5 0 016.364-6.364l2.545 2.545m-6.364 6.364l6.364-6.364" />
                        </svg>
                        <h3 class="text-xl font-bold text-amber-800 mb-2">Under Construction</h3>
                        <p class="text-sm text-amber-700 max-w-md mx-auto">
                            Fitur isian pada tab "Lain-lain" saat ini sedang dalam tahap pengembangan dan diabaikan untuk sementara waktu.<br><br>
                            Seluruh data jemaat dari tab 1 hingga 5 sudah siap. Silakan klik tombol <b>Simpan Data Jemaat</b> di bawah untuk menyelesaikan.
                        </p>
                    </div>
                </div>

            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <button type="button" @click="prevTab()" x-show="activeTab !== 'umum'" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-lg shadow-sm hover:bg-slate-50 transition-colors cursor-pointer outline-none" style="display: none;">
                    &larr; Sebelumnya
                </button>
                <div class="ml-auto flex gap-3">
                    <button type="button" @click="nextTab()" x-show="activeTab !== 'lainnya'" class="px-4 py-2 text-sm font-bold text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors cursor-pointer outline-none">
                        Selanjutnya &rarr;
                    </button>
                    <button type="submit" x-show="activeTab === 'lainnya'" class="px-6 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md shadow-blue-600/20 transition-all cursor-pointer outline-none" style="display: none;">
                        Simpan Data Jemaat
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('jemaatForm', () => ({
            activeTab: 'umum',
            photoPreview: null,
            statusBaptis: '<?= old('status_baptis') ?? 'Belum' ?>',

            // State Orang Tua

            ayahId: '<?= old('orang_tua.ayah_id') ?? '' ?>',
            ayahNama: '<?= old('orang_tua.nama_ayah') ?? '' ?>',
            ayahResults: [],
            showAyahDropdown: false,
            isAyahLoading: false,

            ibuId: '<?= old('orang_tua.ibu_id') ?? '' ?>',
            ibuNama: '<?= old('orang_tua.nama_ibu') ?? '' ?>',
            ibuResults: [],
            showIbuDropdown: false,
            isIbuLoading: false,

            // State Pasangan
            statusPernikahan: '<?= old('status_pernikahan') ?? '' ?>',
            pasanganId: '<?= old('pasangan.id_pasangan') ?? '' ?>',
            pasanganNama: '<?= old('pasangan.nama_pasangan') ?? '' ?>',
            pasanganPekerjaan: '<?= old('pasangan.pekerjaan_pasangan') ?? '' ?>',
            pasanganTempatLahir: '<?= old('pasangan.tempat_lahir_pasangan') ?? '' ?>',
            pasanganTanggalLahir: '<?= old('pasangan.tanggal_lahir_pasangan') ?? '' ?>',
            pasanganTempatNikah: '<?= old('pasangan.tempat_nikah') ?? '' ?>',
            pasanganTanggalNikah: '<?= old('pasangan.tanggal_nikah') ?? '' ?>',
            pasanganResults: [],
            showPasanganDropdown: false,
            isPasanganLoading: false,

            // State Anak (Dilengkapi properti array-nya)
            anakList: <?= old('anak') ? json_encode(old('anak')) : "[{ anak_id: '', nama_anak: '', jenis_kelamin: '', tempat_lahir: '', tanggal_lahir: '', pendidikan: '', status_kristen: '', results: [], showDropdown: false, isLoading: false }]" ?>,
            // State Pekerjaan
            profesiUtama: '<?= old('pekerjaan') ?? '' ?>',

            // Status Pelayanan
            selectedPelayanan: [],
            showPelayananDropdown: false,

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

            // Fungsi Generic untuk Ayah, Ibu, dan Pasangan
            async searchJemaat(tipe) {
                let keyword = '';
                let genderFilter = '';

                // Putuskan ID jika user mengetik ulang manual
                if (tipe === 'ayah') {
                    this.ayahId = '';
                    keyword = this.ayahNama;
                    genderFilter = 'L';
                }
                if (tipe === 'ibu') {
                    this.ibuId = '';
                    keyword = this.ibuNama;
                    genderFilter = 'P';
                }
                if (tipe === 'pasangan') {
                    this.pasanganId = '';
                    keyword = this.pasanganNama;
                } // Pasangan bebas gender

                if (keyword.length < 3) {
                    if (tipe === 'ayah') {
                        this.ayahResults = [];
                        this.showAyahDropdown = false;
                    }
                    if (tipe === 'ibu') {
                        this.ibuResults = [];
                        this.showIbuDropdown = false;
                    }
                    if (tipe === 'pasangan') {
                        this.pasanganResults = [];
                        this.showPasanganDropdown = false;
                    }
                    return;
                }

                if (tipe === 'ayah') this.isAyahLoading = true;
                if (tipe === 'ibu') this.isIbuLoading = true;
                if (tipe === 'pasangan') this.isPasanganLoading = true;

                try {
                    // Meneruskan parameter &jk= (jenis kelamin) ke Controller
                    const response = await fetch(`<?= base_url('jemaat/autocomplete') ?>?q=${encodeURIComponent(keyword)}&jk=${genderFilter}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (tipe === 'ayah') {
                            this.ayahResults = data;
                            this.showAyahDropdown = true;
                        }
                        if (tipe === 'ibu') {
                            this.ibuResults = data;
                            this.showIbuDropdown = true;
                        }
                        if (tipe === 'pasangan') {
                            this.pasanganResults = data;
                            this.showPasanganDropdown = true;
                        }
                    }
                } catch (error) {
                    console.error('Gagal mengambil data:', error);
                } finally {
                    if (tipe === 'ayah') this.isAyahLoading = false;
                    if (tipe === 'ibu') this.isIbuLoading = false;
                    if (tipe === 'pasangan') this.isPasanganLoading = false;
                }
            },

            pilihJemaat(tipe, item) {
                if (tipe === 'ayah') {
                    this.ayahId = item.id;
                    this.ayahNama = item.nama_lengkap;
                    this.showAyahDropdown = false;
                }
                if (tipe === 'ibu') {
                    this.ibuId = item.id;
                    this.ibuNama = item.nama_lengkap;
                    this.showIbuDropdown = false;
                }
                if (tipe === 'pasangan') {
                    this.pasanganId = item.id;
                    this.pasanganNama = item.nama_lengkap;

                    // --- TAMBAHAN AUTOFILL PASANGAN ---
                    // Mengisi form HTML secara otomatis jika data tersedia di database
                    this.pasanganPekerjaan = item.pekerjaan || '';
                    this.pasanganTempatLahir = item.tempat_lahir || '';
                    this.pasanganTanggalLahir = item.tanggal_lahir || '';
                    this.pasanganTempatNikah = item.tempat_nikah || ''; 
                    this.pasanganTanggalNikah = item.tanggal_nikah || '';

                    this.showPasanganDropdown = false;
                }
            },

            // Fungsi Khusus Array Anak
            async searchAnak(index) {
                let anak = this.anakList[index];
                anak.anak_id = ''; // Putuskan ID

                if (anak.nama_anak.length < 3) {
                    anak.results = [];
                    anak.showDropdown = false;
                    return;
                }

                anak.isLoading = true;
                try {
                    const response = await fetch(`<?= base_url('jemaat/autocomplete') ?>?q=${encodeURIComponent(anak.nama_anak)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (response.ok) {
                        anak.results = await response.json();
                        anak.showDropdown = true;
                    }
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    anak.isLoading = false;
                }
            },

            pilihAnak(index, item) {
                this.anakList[index].anak_id = item.id;
                this.anakList[index].nama_anak = item.nama_lengkap;

                // --- TAMBAHAN AUTOFILL ANAK ---
                // Menyuntikkan seluruh data anak yang ditarik dari database ke dalam form
                this.anakList[index].jenis_kelamin = item.jenis_kelamin || '';
                this.anakList[index].tempat_lahir = item.tempat_lahir || '';
                this.anakList[index].tanggal_lahir = item.tanggal_lahir || '';
                this.anakList[index].pendidikan = item.pendidikan || '';

                this.anakList[index].showDropdown = false;
            },

            tambahAnak() {
                this.anakList.push({
                    anak_id: '',
                    nama_anak: '',
                    jenis_kelamin: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    pendidikan: '',
                    status_kristen: '',
                    results: [],
                    showDropdown: false,
                    isLoading: false
                });
            },

            hapusAnak(index) {
                if (this.anakList.length > 1) {
                    this.anakList.splice(index, 1);
                }
            },

            butuhDetailKantor() {
                // Kategori yang TIDAK BUTUH form detail kantor (diambil dari Enum Profesi)
                const tanpaKantor = ['PELAJAR', 'MENGURUS_RT', 'PENSIUNAN', 'BELUM_BEKERJA', ''];
                return !tanpaKantor.includes(this.profesiUtama);
            },

            isPelayananSelected(id) {
                return this.selectedPelayanan.some(item => item.id === id);
            },

            togglePelayanan(id, nama) {
                const index = this.selectedPelayanan.findIndex(item => item.id === id);
                if (index > -1) {
                    // Jika sudah ada di list, hapus (uncheck)
                    this.selectedPelayanan.splice(index, 1);
                } else {
                    // Jika belum ada, masukkan ke list (check)
                    this.selectedPelayanan.push({
                        id: id,
                        nama: nama
                    });
                }
            },

            hapusPelayanan(id) {
                this.selectedPelayanan = this.selectedPelayanan.filter(item => item.id !== id);
            },

            // ... (nextTab & prevTab sama spt sblmnya) ...
            nextTab() {
                let currentIndex = this.tabList.findIndex(t => t.id === this.activeTab);
                if (currentIndex < this.tabList.length - 1) {
                    this.activeTab = this.tabList[currentIndex + 1].id;
                }
            },

            prevTab() {
                let currentIndex = this.tabList.findIndex(t => t.id === this.activeTab);
                if (currentIndex > 0) {
                    this.activeTab = this.tabList[currentIndex - 1].id;
                }
            }

        }));
    });
</script>

<?= $this->endSection() ?>