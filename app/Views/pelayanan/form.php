<?php

/**
 * @var object|null $pelayanan
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $isEdit = isset($pelayanan); ?>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
  <div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800"><?= $isEdit ? 'Edit Pelayanan' : 'Tambah Pelayanan Baru' ?></h2>
  </div>

  <?php if (session()->has('errors')) : ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
      <ul class="list-disc list-inside">
        <?php foreach (session('errors') as $error) : ?>
          <li><?= esc($error) ?></li>
        <?php endforeach ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= base_url($isEdit ? 'pelayanan/update/' . $pelayanan->id : 'pelayanan/store') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="space-y-5">
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pelayanan <span class="text-red-500">*</span></label>
        <input type="text" name="nama_pelayanan" value="<?= old('nama_pelayanan', $isEdit ? $pelayanan->nama_pelayanan : '') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500" required>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Pelayanan</label>
        <textarea name="deskripsi_pelayanan" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500"><?= old('deskripsi_pelayanan', $isEdit ? $pelayanan->deskripsi_pelayanan : '') ?></textarea>
      </div>
    </div>

    <div class="mt-8 flex items-center justify-end gap-3">
      <a href="<?= base_url('pelayanan') ?>" class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</a>
      <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">Simpan</button>
    </div>
  </form>
</div>
<?= $this->endSection() ?>