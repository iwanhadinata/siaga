<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class KeluargaJemaatSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $faker = Factory::create('id_ID');

        // 1. Kosongkan tabel relasi terlebih dahulu agar tidak duplikat jika dijalankan berulang
        $db->table('tbl_jemaat_pasangan')->truncate();
        $db->table('tbl_jemaat_anak')->truncate();
        $db->table('tbl_jemaat_orang_tua')->truncate();

        // 2. Ambil semua data dari 'tbl_jemaat' (id, nama, jenis_kelamin)
        $allJemaat = $db->table('tbl_jemaat')->select('id, nama_lengkap, jenis_kelamin')->get()->getResultArray();

        if (empty($allJemaat)) {
            echo "Tidak ada data jemaat. Silakan jalankan JemaatSeeder terlebih dahulu.\n";
            return;
        }

        // Pisahkan data berdasarkan jenis kelamin untuk akurasi data relasi
        $pria = array_values(array_filter($allJemaat, fn($j) => $j['jenis_kelamin'] === 'L'));
        $wanita = array_values(array_filter($allJemaat, fn($j) => $j['jenis_kelamin'] === 'P'));

        $dataPasangan = [];
        $dataAnak = [];
        $dataOrangTua = [];

        $now = date('Y-m-d H:i:s');

        // 3. Lakukan looping pada list jemaat
        foreach ($allJemaat as $jemaat) {
            $jemaatId = $jemaat['id'];
            $jk = $jemaat['jenis_kelamin'];

            // -- A. DATA PASANGAN (Probabilitas 60%) --
            if ($faker->boolean(60)) {
                // Cari pasangan dari list lawan jenis
                $kandidatPasangan = $jk === 'L' ? $wanita : $pria;

                if (!empty($kandidatPasangan)) {
                    $pasangan = $faker->randomElement($kandidatPasangan);

                    $dataPasangan[] = [
                        'id_jemaat'              => $jemaatId,
                        'id_pasangan'            => $pasangan['id'], // FK TERISI
                        'nama_pasangan'          => $pasangan['nama_lengkap'], // NAMA SINKRON
                        'tempat_lahir_pasangan'  => $faker->city(),
                        'tanggal_lahir_pasangan' => $faker->date('Y-m-d', '-20 years'),
                        'pekerjaan_pasangan'     => $faker->jobTitle(),
                        'tanggal_nikah'          => $faker->date('Y-m-d', '-1 years'),
                        'tempat_nikah'           => $faker->city(),
                        'status'                 => $faker->randomElement(['Hidup', 'Meninggal', 'Cerai']),
                        'created_at'             => $now,
                        'updated_at'             => $now,
                    ];
                }
            }

            // -- B. DATA ORANG TUA (100%) --
            // Ambil Ayah dari list pria, Ibu dari list wanita
            $ayah = !empty($pria) ? $faker->randomElement($pria) : null;
            $ibu = !empty($wanita) ? $faker->randomElement($wanita) : null;

            $dataOrangTua[] = [
                'jemaat_id'  => $jemaatId,
                'ayah_id'    => $ayah ? $ayah['id'] : null, // FK TERISI
                'nama_ayah'  => $ayah ? $ayah['nama_lengkap'] : $faker->name('male'),
                'ibu_id'     => $ibu ? $ibu['id'] : null, // FK TERISI
                'nama_ibu'   => $ibu ? $ibu['nama_lengkap'] : $faker->name('female'),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // -- C. DATA ANAK (Probabilitas 50%) --
            if ($faker->boolean(50)) {
                $jumlahAnak = $faker->numberBetween(1, 3);
                for ($i = 1; $i <= $jumlahAnak; $i++) {
                    // Ambil anak random dari semua jemaat (hindari diri sendiri)
                    $kandidatAnak = array_values(array_filter($allJemaat, fn($j) => $j['id'] !== $jemaatId));

                    if (!empty($kandidatAnak)) {
                        $anak = $faker->randomElement($kandidatAnak);

                        $dataAnak[] = [
                            'jemaat_id'      => $jemaatId,
                            'anak_id'        => $anak['id'], // FK TERISI
                            'urutan'         => $i,
                            'nama_anak'      => $anak['nama_lengkap'], // NAMA SINKRON
                            'jenis_kelamin'  => $anak['jenis_kelamin'],
                            'tempat_lahir'   => $faker->city(),
                            'tanggal_lahir'  => $faker->date('Y-m-d', '-5 years'),
                            'pendidikan'     => $faker->randomElement(['SD', 'SMP', 'SMA', 'S1', 'Belum Sekolah']),
                            'status_kristen' => 'Ya',
                            'created_at'     => $now,
                            'updated_at'     => $now,
                        ];
                    }
                }
            }
        }

        // 4. Eksekusi insertBatch()
        if (!empty($dataPasangan)) {
            $db->table('tbl_jemaat_pasangan')->insertBatch($dataPasangan);
            echo count($dataPasangan) . " data pasangan sukses di-seed dengan FK valid.\n";
        }

        if (!empty($dataOrangTua)) {
            $db->table('tbl_jemaat_orang_tua')->insertBatch($dataOrangTua);
            echo count($dataOrangTua) . " data orang tua sukses di-seed dengan FK valid.\n";
        }

        if (!empty($dataAnak)) {
            $db->table('tbl_jemaat_anak')->insertBatch($dataAnak);
            echo count($dataAnak) . " data anak sukses di-seed dengan FK valid.\n";
        }
    }
}
