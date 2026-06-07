<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-xl font-bold text-slate-800">Master Pelayanan</h2>
      <p class="text-sm text-slate-500">Kelola bidang pelayanan gereja</p>
    </div>
    <a href="<?= base_url('pelayanan/create') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
      + Tambah Pelayanan
    </a>
  </div>

  <?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50">
          <th class="py-3 px-4 text-sm font-bold text-slate-700 w-16 text-center">No</th>
          <th class="py-3 px-4 text-sm font-bold text-slate-700">Nama Pelayanan</th>
          <th class="py-3 px-4 text-sm font-bold text-slate-700">Deskripsi</th>
          <th class="py-3 px-4 text-sm font-bold text-slate-700 text-center w-40">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (empty($pelayanan)) : ?>
          <tr>
            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">Tidak ada data.</td>
          </tr>
        <?php else : ?>
          <?php $i = 1;
          foreach ($pelayanan as $row) : ?>
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="py-3 px-4 text-sm text-slate-600 text-center"><?= $i++ ?></td>
              <td class="py-3 px-4 text-sm font-bold text-slate-800"><?= esc($row->nama_pelayanan) ?></td>
              <td class="py-3 px-4 text-sm text-slate-600"><?= esc($row->deskripsi_pelayanan) ?></td>
              <td class="py-3 px-4 text-sm text-center">
                <a href="<?= base_url('pelayanan/edit/' . $row->id) ?>" class="text-blue-600 hover:text-blue-800 font-bold mr-3">Edit</a>
                <form action="<?= base_url('pelayanan/delete/' . $row->id) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus bidang pelayanan ini?');">
                  <button type="submit" class="text-red-600 hover:text-red-800 font-bold cursor-pointer">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>