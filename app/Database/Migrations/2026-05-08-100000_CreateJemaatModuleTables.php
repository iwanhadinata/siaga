<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJemaatModuleTables extends Migration
{
    // Default atribut untuk MySQL 8
    private array $attributes = ['ENGINE' => 'InnoDB'];

    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nij'              => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'nama_lengkap'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_panggilan'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'foto'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'jenis_kelamin'    => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'default' => 'L'],
            'tempat_lahir'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'    => ['type' => 'DATE', 'null' => true],
            'tempat_baptis'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_baptis'   => ['type' => 'DATE', 'null' => true],
            'golongan_darah'   => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'AB', 'O'], 'null' => true],
            'rhesus'           => ['type' => 'ENUM', 'constraint' => ['Positif', 'Negatif', 'Tidak Tahu'], 'null' => true],
            'status_jemaat'    => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Aktif'],
            'alamat'           => ['type' => 'TEXT', 'null' => true],
            'rt'               => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'rw'               => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'kelurahan'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kabupaten'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kodepos'          => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'hp1'              => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'hp2'              => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email1'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email2'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'audit_trail'      => ['type' => 'JSON', 'null' => true], // Guideline: Metadata/Histori
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nij');
        $this->forge->createTable('jemaats');
    }

    public function down()
    {
        // DROP DARI CHILD KE MASTER UNTUK MENGHINDARI FK CONSTRAINT ERROR
        $tables = [
            'tbl_jemaat_pelayanan',
            'tbl_jemaat_pendidikan',
            'tbl_jemaat_orang_tua',
            'tbl_jemaat_anak',
            'tbl_jemaat_pasangan',
            'tbl_pelayanan',
            'tbl_jemaat'
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    /* ==========================================================================
     * HELPER METHODS (Menerapkan DRY Principle & PHP 8.2 Array Unpacking)
     * ========================================================================== */

    private function pk(): array
    {
        return ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true];
    }

    // FIX: Tambahkan parameter $nullable agar mendukung ON DELETE SET NULL
    private function fk(string $comment = '', bool $nullable = false): array
    {
        return [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
            'null'       => $nullable, // True jika FK boleh kosong (opsional)
            'comment'    => $comment
        ];
    }

    private function timestamps(bool $softDelete = true): array
    {
        $stamps = [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if ($softDelete) {
            $stamps['deleted_at'] = ['type' => 'DATETIME', 'null' => true];
        }

        return $stamps;
    }

    /* ==========================================================================
     * TABLE DEFINITIONS
     * ========================================================================== */

    private function createTblJemaat()
    {
        $this->forge->addField([
            'id'                => $this->pk(),
            'nij'               => ['type' => 'VARCHAR', 'constraint' => 20, 'comment' => 'Unique identifier'],
            'nama_lengkap'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'nama_panggilan'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tempat_lahir'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'     => ['type' => 'DATE', 'null' => true],
            'pekerjaan'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_baptis'    => ['type' => 'DATE', 'null' => true],
            'tempat_baptis'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'golongan_darah'    => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'AB', 'O', 'Tidak Tahu'], 'null' => true],
            'rhesus'            => ['type' => 'ENUM', 'constraint' => ['+', '-', 'Tidak Tahu'], 'null' => true],
            'jenis_kelamin'     => ['type' => 'ENUM', 'constraint' => ['L', 'P']],
            'status_pernikahan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status_jemaat'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'alamat'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'rt'                => ['type' => 'VARCHAR', 'constraint' => 3, 'null' => true],
            'rw'                => ['type' => 'VARCHAR', 'constraint' => 3, 'null' => true],
            'kelurahan'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kecamatan'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kabupaten'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kodepos'           => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'hp1'               => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'hp2'               => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'email1'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email2'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'foto_url'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'audit_trail'       => ['type' => 'JSON', 'null' => true],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nij');
        $this->forge->addKey('nama_lengkap');
        $this->forge->createTable('tbl_jemaat', true, $this->attributes);
    }

    private function createTblPelayanan()
    {
        $this->forge->addField([
            'id'                  => $this->pk(),
            'nama_pelayanan'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'deskripsi_pelayanan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tbl_pelayanan', true, $this->attributes);
    }

    private function createTblJemaatPasangan()
    {
        $this->forge->addField([
            'id'                     => $this->pk(),
            'id_jemaat'              => $this->fk('FK ke jemaat utama', false),
            // FIX: Set true untuk mengizinkan NULL agar SET NULL di constraint berhasil
            'id_pasangan'            => $this->fk('FK ke jemaat pasangan (opsional)', true),
            'nama_pasangan'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'tempat_lahir_pasangan'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir_pasangan' => ['type' => 'DATE', 'null' => true],
            'pekerjaan_pasangan'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_nikah'          => ['type' => 'DATE', 'null' => true],
            'tempat_nikah'           => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'status'                 => ['type' => 'VARCHAR', 'constraint' => 50],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_jemaat', 'tbl_jemaat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pasangan', 'tbl_jemaat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_jemaat_pasangan', true, $this->attributes);
    }

    private function createTblJemaatAnak()
    {
        $this->forge->addField([
            'id'             => $this->pk(),
            'jemaat_id'      => $this->fk('FK ke tabel jemaat', false),
            // FIX: Set true untuk mengizinkan NULL
            'anak_id'        => $this->fk('Opsional jika anak terdaftar sbg jemaat', true),
            'urutan'         => ['type' => 'TINYINT', 'constraint' => 3],
            'nama_anak'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'jenis_kelamin'  => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'null' => true],
            'tempat_lahir'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'  => ['type' => 'DATE', 'null' => true],
            'pendidikan'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status_kristen' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('jemaat_id', 'tbl_jemaat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('anak_id', 'tbl_jemaat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_jemaat_anak', true, $this->attributes);
    }

    private function createTblJemaatOrangTua()
    {
        $this->forge->addField([
            'id'         => $this->pk(),
            'jemaat_id'  => $this->fk('FK ke tabel jemaat', false),
            // FIX: Set true untuk mengizinkan NULL
            'ayah_id'    => $this->fk('Opsional', true),
            'nama_ayah'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            // FIX: Set true untuk mengizinkan NULL
            'ibu_id'     => $this->fk('Opsional', true),
            'nama_ibu'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ...$this->timestamps(false) // Tidak butuh deleted_at
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('jemaat_id', 'tbl_jemaat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ayah_id', 'tbl_jemaat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('ibu_id', 'tbl_jemaat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_jemaat_orang_tua', true, $this->attributes);
    }

    private function createTblJemaatPendidikan()
    {
        $this->forge->addField([
            'id'                => $this->pk(),
            'jemaat_id'         => $this->fk('FK ke tabel jemaat', false),
            'sd_di'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'sd_tahun'          => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'smp_di'            => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'smp_tahun'         => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'sma_di'            => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'sma_tahun'         => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            's1_di'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            's1_tahun'          => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            's2_di'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            's2_tahun'          => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            's3_di'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            's3_tahun'          => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'gelar_sarjana'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'bidang_pendidikan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('jemaat_id'); // 1-to-1 relationship
        $this->forge->addForeignKey('jemaat_id', 'tbl_jemaat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_jemaat_pendidikan', true, $this->attributes);
    }

    private function createTblJemaatPelayanan()
    {
        $this->forge->addField([
            'id'           => $this->pk(),
            'jemaat_id'    => $this->fk('FK ke tabel jemaat', false),
            'pelayanan_id' => $this->fk('FK ke tabel pelayanan', false),
            'audit_trail'  => ['type' => 'JSON', 'null' => true],
            ...$this->timestamps()
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['jemaat_id', 'pelayanan_id']); // Composite Index
        $this->forge->addKey('pelayanan_id');
        $this->forge->addForeignKey('jemaat_id', 'tbl_jemaat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pelayanan_id', 'tbl_pelayanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_jemaat_pelayanan', true, $this->attributes);
    }
}
