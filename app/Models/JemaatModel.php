<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Jemaat;

class JemaatModel extends Model
{
    protected $table            = 'tbl_jemaat'; // Sesuaikan dengan Migration
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Jemaat::class; // Kembalikan sebagai Entity
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'nij',
        'nama_lengkap',
        'nama_panggilan',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'tempat_baptis',
        'tanggal_baptis',
        'golongan_darah',
        'rhesus',
        'jenis_kelamin',
        'status_pernikahan',
        'status_jemaat',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'kodepos',
        'hp1',
        'hp2',
        'email1',
        'email2',
        'foto_url',
        'audit_trail',
    ];

    protected $useTimestamps = true;

    /**
     * Business Logic: Simpan Jemaat komplit beserta relasinya
     * Mencegah N+1 dengan menggunakan Batch Insert
     */
    public function simpanJemaatKomplit(
        Jemaat $jemaatEntity,
        array $dataAnak = [],
        array $dataPasangan = [],
        array $dataOrangTua = [],
        array $dataPekerjaanDetail = [],
        array $dataPendidikan = [],
        array $dataPelayanan = []
    ): bool {
        $db = \Config\Database::connect();
        $db->transException(true);
        $db->transStart();

        try {
            $currentTimestamp = date('Y-m-d H:i:s');

            // 1. Insert Parent (tbl_jemaat)
            $this->insert($jemaatEntity);
            $jemaatId = $this->getInsertID();

            // 2. Insert Pasangan (jika ada nama pasangan)
            if (!empty($dataPasangan['nama_pasangan'])) {
                $dataPasangan['id_jemaat'] = $jemaatId;

                // Ambil nilai status dari field status_pernikahan jemaat utama
                $dataPasangan['status']     = $jemaatEntity->status_pernikahan;
                $dataPasangan['created_at'] = $currentTimestamp;
                $dataPasangan['updated_at'] = $currentTimestamp;

                if (empty($dataPasangan['id_pasangan'])) {
                    unset($dataPasangan['id_pasangan']);
                }
                $db->table('tbl_jemaat_pasangan')->insert($dataPasangan);
            }

            // 3. Insert Anak
            if (!empty($dataAnak)) {
                $batchAnak = [];
                foreach ($dataAnak as $index => $anak) {
                    if (!empty($anak['nama_anak'])) {
                        $dataInsertAnak = [
                            'jemaat_id'      => $jemaatId,
                            'urutan'         => $index + 1,
                            'nama_anak'      => $anak['nama_anak'],
                            'jenis_kelamin'  => $anak['jenis_kelamin'] ?? null,
                            'tempat_lahir'   => $anak['tempat_lahir'] ?? null,
                            'tanggal_lahir'  => empty($anak['tanggal_lahir']) ? null : $anak['tanggal_lahir'],
                            'pendidikan'     => $anak['pendidikan'] ?? null,
                            'status_kristen' => !empty($anak['status_kristen']) ? $anak['status_kristen'] : 'Belum',
                            'created_at'     => $currentTimestamp, // <- Set manual timestamp anak
                            'updated_at'     => $currentTimestamp
                        ];

                        if (!empty($anak['anak_id'])) {
                            $dataInsertAnak['anak_id'] = $anak['anak_id'];
                        }
                        $batchAnak[] = $dataInsertAnak;
                    }
                }
                if (!empty($batchAnak)) {
                    $db->table('tbl_jemaat_anak')->insertBatch($batchAnak);
                }
            }

            // 4. Insert Orang Tua (jika ada input nama ayah / ibu)
            if (!empty($dataOrangTua['nama_ayah']) || !empty($dataOrangTua['nama_ibu'])) {
                $dataInsertOrtu = [
                    'jemaat_id'       => $jemaatId,
                    'nama_ayah'       => $dataOrangTua['nama_ayah'] ?? null,
                    'nama_ibu'        => $dataOrangTua['nama_ibu'] ?? null,
                    'created_at'      => $currentTimestamp,
                    'updated_at'      => $currentTimestamp
                ];

                // FIX FOREIGN KEY: Hanya masukkan ayah_id ke query jika benar-benar ada isinya
                if (!empty($dataOrangTua['ayah_id'])) {
                    $dataInsertOrtu['ayah_id'] = $dataOrangTua['ayah_id'];
                }

                // FIX FOREIGN KEY: Hanya masukkan ibu_id ke query jika benar-benar ada isinya
                if (!empty($dataOrangTua['ibu_id'])) {
                    $dataInsertOrtu['ibu_id'] = $dataOrangTua['ibu_id'];
                }

                $db->table('tbl_jemaat_orang_tua')->insert($dataInsertOrtu);
            }

            // 5. Insert Detail Pekerjaan (tbl_jemaat_pekerjaan)
            if (!empty($dataPekerjaanDetail['nama_kantor']) || !empty($dataPekerjaanDetail['jabatan'])) {
                $dataPekerjaanDetail['jemaat_id']  = $jemaatId;
                $dataPekerjaanDetail['created_at'] = $currentTimestamp;
                $dataPekerjaanDetail['updated_at'] = $currentTimestamp;

                $db->table('tbl_jemaat_pekerjaan')->insert($dataPekerjaanDetail);
            }

            // 6. Insert Pendidikan (tbl_jemaat_pendidikan)
            if (!empty($dataPendidikan['jenjang_terakhir'])) {
                $dataPendidikan['jemaat_id']  = $jemaatId;
                $dataPendidikan['created_at'] = $currentTimestamp;
                $dataPendidikan['updated_at'] = $currentTimestamp;

                $db->table('tbl_jemaat_pendidikan')->insert($dataPendidikan);
            }

            // 7. Insert Bidang Pelayanan Aktif (tbl_jemaat_pelayanan)
            if (!empty($dataPelayanan)) {
                $batchPelayanan = [];
                foreach ($dataPelayanan as $pelayananId) {
                    $batchPelayanan[] = [
                        'jemaat_id'    => $jemaatId,
                        'pelayanan_id' => $pelayananId,
                        'created_at'   => $currentTimestamp,
                        'updated_at'   => $currentTimestamp
                    ];
                }
                $db->table('tbl_jemaat_pelayanan')->insertBatch($batchPelayanan);
            }

            $db->transComplete();
            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('critical', '[Jemaat Store Failed] ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Server-Side Processing: Ambil data dengan filter, sort, dan limit
     */
    public function getJemaatSSP(string $search = '', string $sortCol = 'nama_lengkap', string $sortDir = 'ASC', int $limit = 10, int $offset = 0)
    {
        if (!empty($search)) {
            $this->groupStart()
                ->like('nama_lengkap', $search, 'both', null, true)
                ->orLike('nij', $search, 'both', null, true)
                ->groupEnd();
        }

        return $this->orderBy($sortCol, $sortDir)->findAll($limit, $offset);
    }

    /**
     * Server-Side Processing: Hitung total data terfilter
     */
    public function countJemaatSSP(string $search = '')
    {
        if (!empty($search)) {
            $this->groupStart()
                ->like('nama_lengkap', $search, 'both', null, true)
                ->orLike('nij', $search, 'both', null, true)
                ->groupEnd();
        }

        return $this->countAllResults();
    }

    /**
     * Autocomplete Search: Mencari jemaat untuk form pencarian cepat
     * Mengembalikan data array, bukan entity agar ringan di-encode JSON
     */
    public function searchAutocomplete(string $keyword, int $limit = 5): array
    {
        return $this->select('tbl_jemaat.id, tbl_jemaat.nij, tbl_jemaat.nama_lengkap, tbl_jemaat.status_jemaat, tbl_jemaat.hp1, tbl_jemaat.pekerjaan, tbl_jemaat.tempat_lahir, tbl_jemaat.tanggal_lahir, tbl_jemaat.jenis_kelamin, tbl_jemaat_pasangan.tempat_nikah, tbl_jemaat_pasangan.tanggal_nikah')
            ->join('tbl_jemaat_pasangan', 'tbl_jemaat_pasangan.id_jemaat = tbl_jemaat.id', 'left') // Join tabel pasangan
            ->groupStart()
            ->like('tbl_jemaat.nama_lengkap', $keyword, 'both', null, true)
            ->orLike('tbl_jemaat.nij', $keyword, 'both', null, true)
            ->groupEnd()
            ->orderBy('tbl_jemaat.nama_lengkap', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
