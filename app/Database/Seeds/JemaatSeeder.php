<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class JemaatSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $data = [];

        $jenisKelamin = ['L', 'P'];
        $statusPernikahan = ['belum_menikah', 'menikah', 'cerai_hidup', 'cerai_mati'];

        for ($i = 0; $i < 100; $i++) {
            $jk = $faker->randomElement($jenisKelamin);

            $data[] = [
                'nij'               => $faker->unique()->numerify('NIJ-####-####'),
                'nama_lengkap'      => $faker->name($jk === 'L' ? 'male' : 'female'),
                'nama_panggilan'    => $faker->firstName($jk === 'L' ? 'male' : 'female'),
                'tempat_lahir'      => $faker->city(),
                'tanggal_lahir'     => $faker->date('Y-m-d', '2010-01-01'),
                'pekerjaan'         => $faker->jobTitle(),
                'jenis_kelamin'     => $jk,
                'status_pernikahan' => $faker->randomElement($statusPernikahan),
                'alamat'            => $faker->address(),
                'hp1'               => substr($faker->phoneNumber(), 0, 15), // Batasi panjang sesuai DB constraint
                'email1'            => $faker->unique()->safeEmail(),
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
        }

        // Insert using Query Builder
        $this->db->table('tbl_jemaat')->insertBatch($data);
    }
}
