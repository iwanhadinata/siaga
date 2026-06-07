<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * Karena sistem ini tertutup (tidak ada fitur register publik untuk jemaat),
     * kita set default ke group yang memiliki akses paling terbatas jika terjadi
     * pembuatan user secara sistematik.
     */
    public string $defaultGroup = 'ketua_komisi';

    /**
     * --------------------------------------------------------------------
     * Groups / Roles
     * --------------------------------------------------------------------
     */
    public array $groups = [
        'superadmin'   => [
            'title'       => 'Super Administrator',
            'description' => 'Akses absolut ke seluruh sistem, konfigurasi, dan database.',
        ],
        'sekretariat'  => [
            'title'       => 'Tata Usaha / Sekretariat',
            'description' => 'Mengelola master data jemaat, keluarga, presensi, dan persuratan.',
        ],
        'bendahara'    => [
            'title'       => 'Bendahara',
            'description' => 'Mengelola sirkulasi keuangan, persembahan, dan anggaran komisi.',
        ],
        'ketua_komisi' => [
            'title'       => 'Ketua Komisi',
            'description' => 'Akses manajerial spesifik sesuai bidang komisi masing-masing.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions (Hak Akses Spesifik)
     * --------------------------------------------------------------------
     */
    public array $permissions = [
        'admin.access'    => 'Boleh masuk ke dalam aplikasi (Dashboard Admin)',
        'jemaat.manage'   => 'Boleh menambah, mengedit, dan melihat entitas Jemaat & Keluarga',
        'keuangan.manage' => 'Boleh mencatat pemasukan dan pengeluaran sistem',
        'keuangan.view'   => 'Boleh melihat laporan keuangan',
        'komisi.manage'   => 'Boleh mengelola program kerja dan anggota komisinya sendiri',
        'settings.manage' => 'Boleh mengubah pengaturan inti aplikasi',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     */
    public array $matrix = [
        'superadmin' => [
            '*', // Akses penuh ke semua permission
        ],
        'sekretariat' => [
            'admin.access',
            'jemaat.manage',
            // Sekretariat mungkin perlu melihat laporan komisi, tapi kita batasi scope-nya nanti di Controller
        ],
        'bendahara' => [
            'admin.access',
            'keuangan.manage',
            'keuangan.view',
            // Bendahara bisa melihat data jemaat untuk keperluan pencatatan nama penyumbang/persembahan
            'jemaat.view', 
        ],
        'ketua_komisi' => [
            'admin.access',
            'komisi.manage',
            // Ketua komisi butuh melihat laporan keuangan spesifik anggaran komisinya saja
            'keuangan.view', 
        ],
    ];
}