<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class AuthSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil instance dari User Provider bawaan Shield
        $users = auth()->getProvider();

        echo "Mengecek ketersediaan akun Superadmin...\n";

        // Mencegah duplikasi data jika seeder dijalankan ulang
        if ($users->where('username', 'superadmin')->first()) {
            echo "Akun Superadmin sudah ada. Melewati proses seeding.\n";
            return;
        }

        // 2. Gunakan User Entity CI Shield untuk mengisi data
        $user = new User([
            'username' => 'superadmin',
            'email'    => 'admin@gereja.local', // Shield menggunakan email untuk identitas login dasar
            'password' => 'RahasiaGereja2026!', // Password akan di-hash secara otomatis oleh Entity
        ]);

        // 3. Simpan user ke tabel (akan mengisi tabel 'users' dan 'auth_identities')
        $users->save($user);

        // Ambil ID user yang baru saja dibuat
        $userId = $users->getInsertID();
        $user = $users->findById($userId);

        // 4. Assign role 'superadmin' dan aktifkan akun
        if ($user) {
            $user->addGroup('superadmin');
            $user->activate(); // Mengubah status 'active' menjadi 1
            echo "Berhasil! Akun superadmin dibuat (Username: superadmin | Email: admin@gereja.local).\n";
        } else {
            echo "Gagal membuat akun superadmin.\n";
        }
    }
}