<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'SIAGA') ?> — Sistem Informasi & Administrasi Gereja</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">

  <link href="<?= base_url('css/output.css') ?>" rel="stylesheet">

  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <style>
    .custom-scrollbar::-webkit-scrollbar {
      width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
  </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

  <div class="flex min-h-screen">
    <?= $this->include('layouts/sidebar') ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto custom-scrollbar">
      <?= $this->include('layouts/header') ?>

      <div class="px-4 md:px-6 pt-4">
        <?php
        // 1. Parsing Flashdata Success
        $rawSuccess = session()->getFlashdata('success');
        $successMsg = is_array($rawSuccess) ? implode(', ', $rawSuccess) : (string) $rawSuccess;

        // 2. Parsing Flashdata Error
        $rawError = session()->getFlashdata('error');
        $errorMsg = is_array($rawError) ? implode(', ', $rawError) : (string) $rawError;
        ?>

        <?php if (!empty($successMsg)): ?>
          <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-[13px] mb-4 font-medium">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1">
              <?= esc($successMsg) ?>
            </span>
            <button type="button" @click="show = false" class="opacity-50 hover:opacity-100"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg></button>
          </div>
        <?php endif; ?>

        <?php if (!empty($errorMsg)): ?>
          <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13px] mb-4 font-medium">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1">
              <?= esc($errorMsg) ?>
            </span>
            <button type="button" @click="show = false" class="opacity-50 hover:opacity-100"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg></button>
          </div>
        <?php endif; ?>
      </div>

      <main class="flex-1 px-4 md:px-6 pb-6">
        <?= $this->renderSection('content') ?>
      </main>

      <?= $this->include('layouts/footer') ?>
    </div>
  </div>

  <script>
    window.addEventListener('pageshow', function(event) {
      var isCached = event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2);
      if (isCached) window.location.reload(true);
    });
  </script>
</body>

</html>