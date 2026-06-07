<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-surface rounded-lg p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Total Jemaat</h3>
        <p class="text-3xl font-bold text-primary">0</p>
    </div>
    <div class="bg-surface rounded-lg p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Total Pelayanan</h3>
        <p class="text-3xl font-bold text-primary">0</p>
    </div>
    <div class="bg-surface rounded-lg p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Kas Bulan Ini</h3>
        <p class="text-3xl font-bold text-primary">Rp 0</p>
    </div>
    <div class="bg-surface rounded-lg p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Jadwal Terdekat</h3>
        <p class="text-3xl font-bold text-primary">0</p>
    </div>
</div>

<div class="bg-surface rounded-lg p-6 shadow-sm border border-gray-100">
    <h2 class="text-xl font-bold text-primary mb-4">Selamat Datang di Dashboard SIAGA</h2>
    <p class="text-gray-600">Sistem Informasi dan Administrasi Gereja. Anda dapat mengelola data jemaat, pelayanan, dan administrasi lainnya melalui menu yang tersedia.</p>
</div>
<?= $this->endSection() ?>
