<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class JemaatRelationsSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        
        // Ambil semua jemaat yang ada
        $jemaats = $this->db->table('tbl_jemaat')->get()->getResult();
        
        if (empty($jemaats)) {
            echo "Tidak ada data jemaat. Jalankan JemaatSeeder terlebih dahulu.\n";
            return;
        }

        // Kosongkan tabel relasi terlebih dahulu agar tidak duplikat jika dijalankan berulang
        $this->db->table('tbl_jemaat_pasangan')->truncate();
        $this->db->table('tbl_jemaat_anak')->truncate();
        $this->db->table('tbl_jemaat_orang_tua')->truncate();

        $pasanganData = [];
        $anakData = [];
        $orangTuaData = [];

        $statusPernikahanValid = ['menikah', 'cerai_hidup', 'cerai_mati'];

        foreach ($jemaats as $jemaat) {
            // 1. Seeder Pasangan
            if (in_array($jemaat->status_pernikahan, $statusPernikahanValid)) {
                $jkPasangan = $jemaat->jenis_kelamin === 'L' ? 'P' : 'L';
                $statusPasangan = 'Aktif'; 
                if ($jemaat->status_pernikahan === 'cerai_hidup') $statusPasangan = 'Cerai Hidup';
                if ($jemaat->status_pernikahan === 'cerai_mati') $statusPasangan = 'Meninggal';

                $pasanganData[] = [
                    'id_jemaat'              => $jemaat->id,
                    'id_pasangan'            => null, // Anggap pasangan belum terdaftar sbg jemaat mandiri (nullable FK)
                    'nama_pasangan'          => $faker->name($jkPasangan === 'L' ? 'male' : 'female'),
                    'tempat_lahir_pasangan'  => $faker->city(),
                    'tanggal_lahir_pasangan' => $faker->date('Y-m-d', '-20 years'),
                    'pekerjaan_pasangan'     => $faker->jobTitle(),
                    'tanggal_nikah'          => $faker->date('Y-m-d', '-1 years'),
                    'tempat_nikah'           => $faker->city(),
                    'status'                 => $statusPasangan,
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s'),
                ];
            }

            // 2. Seeder Anak (Contoh: 60% yang pernah menikah akan punya 1-3 anak)
            if ($faker->boolean(60) && in_array($jemaat->status_pernikahan, $statusPernikahanValid)) {
                $jmlAnak = $faker->numberBetween(1, 3);
                for ($i = 1; $i <= $jmlAnak; $i++) {
                    $jkAnak = $faker->randomElement(['L', 'P']);
                    $anakData[] = [
                        'jemaat_id'      => $jemaat->id,
                        'anak_id'        => null, // Nullable FK
                        'urutan'         => $i,
                        'nama_anak'      => $faker->name($jkAnak === 'L' ? 'male' : 'female'),
                        'jenis_kelamin'  => $jkAnak,
                        'tempat_lahir'   => $faker->city(),
                        'tanggal_lahir'  => $faker->date('Y-m-d', '-5 years'),
                        'pendidikan'     => $faker->randomElement(['SD', 'SMP', 'SMA', 'S1', 'Belum Sekolah']),
                        'status_kristen' => $faker->randomElement(['Sudah Baptis', 'Belum Baptis']),
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ];
                }
            }

            // 3. Seeder Orang Tua (Setiap jemaat dianggap punya data orang tua minimal namanya)
            $orangTuaData[] = [
                'jemaat_id'  => $jemaat->id,
                'ayah_id'    => null, // Nullable FK
                'nama_ayah'  => $faker->name('male'),
                'ibu_id'     => null, // Nullable FK
                'nama_ibu'   => $faker->name('female'),
            ];
        }

        // Eksekusi Insert Batch
        if (!empty($pasanganData)) {
            $this->db->table('tbl_jemaat_pasangan')->insertBatch($pasanganData);
        }
        if (!empty($anakData)) {
            $this->db->table('tbl_jemaat_anak')->insertBatch($anakData);
        }
        if (!empty($orangTuaData)) {
            $this->db->table('tbl_jemaat_orang_tua')->insertBatch($orangTuaData);
        }
    }
}
