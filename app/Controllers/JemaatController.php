<?php

namespace App\Controllers;

use App\Models\JemaatModel;
use App\Models\PelayananModel;

use App\Entities\Jemaat;

use App\Enums\JenisKelamin;
use App\Enums\StatusJemaat;
use App\Enums\GolonganDarah;
use App\Enums\Rhesus;
use App\Enums\StatusPernikahan;
use App\Enums\Pendidikan;
use App\Enums\Profesi;

class JemaatController extends BaseController
{
    protected JemaatModel $jemaatModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->jemaatModel = new JemaatModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Daftar Jemaat',
            'breadcrumb' => 'Data Master / Jemaat',
        ];
        return view('jemaat/index', $data);
    }

    public function ajaxList()
    {
        $search  = (string) ($this->request->getGet('search') ?? '');
        $sortCol = (string) ($this->request->getGet('sort') ?? 'nama_lengkap');
        $sortDir = (string) ($this->request->getGet('dir') ?? 'ASC');
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $limit   = (int) ($this->request->getGet('limit') ?? 10);

        $offset  = ($page - 1) * $limit;

        $data  = $this->jemaatModel->getJemaatSSP($search, $sortCol, $sortDir, $limit, $offset);
        $total = $this->jemaatModel->countJemaatSSP($search);

        return $this->response->setJSON([
            'data'         => $data,
            'total'        => $total,
            'current_page' => $page,
            'total_pages'  => ceil($total / $limit)
        ]);
    }

    public function create()
    {
        $pelayananModel = new PelayananModel();

        $data = [
            'title'      => 'Tambah Data Jemaat',
            'breadcrumb' => 'Data Master / Jemaat / Tambah',
            'enums'      => [
                'jenis_kelamin'     => JenisKelamin::cases(),
                'status_jemaat'     => StatusJemaat::cases(),
                'golongan_darah'    => GolonganDarah::cases(),
                'rhesus'            => Rhesus::cases(),
                'status_pernikahan' => StatusPernikahan::cases(),
                'pendidikan'        => Pendidikan::cases(),
                'profesi'           => Profesi::cases(),
                'pelayanan'         => $pelayananModel->getActivePelayanan(),
            ]
        ];
        return view('jemaat/form', $data);
    }

    public function store()
    {
        $rules = [
            // Tab 1: Umum
            'nama_lengkap'       => 'required|min_length[3]|max_length[100]',
            'nij'                => 'permit_empty|max_length[20]',
            'nama_panggilan'     => 'permit_empty|max_length[100]',
            'jenis_kelamin'      => 'required|in_list[' . implode(',', array_column(JenisKelamin::cases(), 'value')) . ']',
            'status_jemaat'      => 'permit_empty|in_list[' . implode(',', array_column(StatusJemaat::cases(), 'value')) . ']',
            'golongan_darah'     => 'permit_empty|in_list[' . implode(',', array_column(GolonganDarah::cases(), 'value')) . ']',
            'rhesus'             => 'permit_empty|in_list[' . implode(',', array_column(Rhesus::cases(), 'value')) . ']',
            'tempat_lahir'       => 'permit_empty|max_length[100]',
            'tanggal_lahir'      => 'permit_empty|valid_date[Y-m-d]',
            'tempat_baptis'      => 'permit_empty|max_length[100]',
            'tanggal_baptis'     => 'permit_empty|valid_date[Y-m-d]',
            'alamat'             => 'permit_empty|max_length[255]',
            'rt'                 => 'permit_empty|max_length[3]',
            'rw'                 => 'permit_empty|max_length[3]',
            'kelurahan'          => 'permit_empty|max_length[100]',
            'kecamatan'          => 'permit_empty|max_length[100]',
            'kabupaten'          => 'permit_empty|max_length[100]',
            'kodepos'            => 'permit_empty|max_length[5]',
            'hp1'                => 'permit_empty|max_length[15]',
            'hp2'                => 'permit_empty|max_length[15]',
            'email1'             => 'permit_empty|valid_email|max_length[100]',
            'email2'             => 'permit_empty|valid_email|max_length[100]',
            'foto'               => 'permit_empty|uploaded[foto]|max_size[foto,2048]|is_image[foto]',

            // Tab 2: Keluarga
            'status_pernikahan'      => 'required|in_list[' . implode(',', array_column(StatusPernikahan::cases(), 'value')) . ']',
            'pasangan.nama_pasangan' => 'permit_empty|max_length[100]',
            'anak.*.nama_anak'       => 'permit_empty|max_length[100]',

            // Tab 3: Pekerjaan
            'pekerjaan'              => 'required|in_list[' . implode(',', array_column(Profesi::cases(), 'name')) . ']',

            // Tab 4: Pendidikan
            'pendidikan.jenjang_terakhir' => 'permit_empty|in_list[' . implode(',', array_column(Pendidikan::cases(), 'value')) . ']',

            // Tab 6 DIABAIKAN (Tidak ada rule untuk status_keanggotaan dan catatan)
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jemaat = new Jemaat($this->request->getPost());

        // Handle Upload Foto
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/jemaat', $newName);

            // FIX: Map ke foto_url sesuai nama field di tabel / model
            $jemaat->foto_url = $newName;
        }

        // Audit Trail 
        $jemaat->audit_trail = [
            'created_by' => auth()->id(),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'action'     => 'CREATE_NEW'
        ];

        // Eksekusi ke Model
        try {
            $this->jemaatModel->simpanJemaatKomplit(
                $jemaat,
                $this->request->getPost('anak') ?? [],
                $this->request->getPost('pasangan') ?? [],
                $this->request->getPost('orang_tua') ?? [],        
                $this->request->getPost('pekerjaan_detail') ?? [], 
                $this->request->getPost('pendidikan') ?? [],        
                $this->request->getPost('pelayanan') ?? []          
            );

            return redirect()->to('jemaat')->with('success', 'Data berhasil disimpan.');
            // } catch (\Exception $e) {
            //     log_message('error', '[Jemaat Store] ' . $e->getMessage());
            //     return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
            // }
        } catch (\Exception $e) {
            log_message('error', '[Jemaat Store] ' . $e->getMessage());
            // Menampilkan pesan error spesifik dari database ke layar
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Render View Form Pencarian
     */
    public function search()
    {
        $id = $this->request->getGet('id');
        $jemaat = null;

        // Jika ada ID dari klik Autocomplete, tarik datanya dari database
        if ($id) {
            $jemaat = $this->jemaatModel->find($id);
        }
        $data = [
            'title'      => 'Pencarian Data Jemaat',
            'breadcrumb' => 'Data Master / Jemaat / Pencarian',
            'jemaat'     => $jemaat,
            'enums'      => [
                'jenis_kelamin'     => JenisKelamin::cases(),
                'status_jemaat'     => StatusJemaat::cases(),
                'golongan_darah'    => GolonganDarah::cases(),
                'rhesus'            => Rhesus::cases(),
                'status_pernikahan' => StatusPernikahan::cases(),
                'pendidikan'        => Pendidikan::cases(),
                'profesi'           => Profesi::cases(),
            ]
        ];
        return view('jemaat/search', $data);
    }

    /**
     * Endpoint API (AJAX) untuk Autocomplete AlpineJS
     */
    public function autocomplete()
    {
        // Proteksi route: Pastikan hanya diakses via AJAX (Fetch API)
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak. Hanya menerima request AJAX.']);
        }

        $keyword = (string) $this->request->getGet('q');

        // Jika keyword kurang dari 3 karakter, kembalikan array kosong (mengurangi beban server)
        if (strlen($keyword) < 3) {
            return $this->response->setJSON([]);
        }

        $results = $this->jemaatModel->searchAutocomplete($keyword);

        return $this->response->setJSON($results);
    }

    public function detail(string $id)
    {
        // Cari data jemaat berdasarkan ID
        $jemaat = $this->jemaatModel->find($id);

        if (!$jemaat) {
            return redirect()->to('jemaat/search')->with('error', 'Data jemaat tidak ditemukan.');
        }

        $data = [
            'title'      => 'Detail Data Jemaat',
            'breadcrumb' => 'Data Master / Jemaat / Detail',
            'jemaat'     => $jemaat // Passing data jemaat ke view
        ];

        return view('jemaat/detail', $data);
    }
}
