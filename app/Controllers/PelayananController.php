<?php

namespace App\Controllers;

use App\Models\PelayananModel;

class PelayananController extends BaseController
{
  protected PelayananModel $pelayananModel;
  protected $helpers = ['form'];

  public function __construct()
  {
    $this->pelayananModel = new PelayananModel();
  }

  public function index()
  {
    $data = [
      'title'      => 'Master Data Pelayanan',
      'breadcrumb' => 'Data Master / Pelayanan',
      'pelayanan'  => $this->pelayananModel->orderBy('nama_pelayanan', 'ASC')->findAll()
    ];

    return view('pelayanan/index', $data);
  }

  public function create()
  {
    $data = [
      'title'      => 'Tambah Pelayanan',
      'breadcrumb' => 'Data Master / Pelayanan / Tambah',
    ];

    return view('pelayanan/form', $data);
  }

  public function store()
  {
    $rules = [
      'nama_pelayanan'      => 'required|min_length[3]|max_length[100]',
      'deskripsi_pelayanan' => 'permit_empty|max_length[255]'
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->pelayananModel->insert($this->request->getPost());

    return redirect()->to('pelayanan')->with('success', 'Data pelayanan berhasil ditambahkan.');
  }

  public function edit(int $id)
  {
    $pelayanan = $this->pelayananModel->find($id);

    if (!$pelayanan) {
      return redirect()->to('pelayanan')->with('error', 'Data tidak ditemukan.');
    }

    $data = [
      'title'      => 'Edit Pelayanan',
      'breadcrumb' => 'Data Master / Pelayanan / Edit',
      'pelayanan'  => $pelayanan
    ];

    return view('pelayanan/form', $data);
  }

  public function update(int $id)
  {
    $rules = [
      'nama_pelayanan'      => 'required|min_length[3]|max_length[100]',
      'deskripsi_pelayanan' => 'permit_empty|max_length[255]'
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->pelayananModel->update($id, $this->request->getPost());

    return redirect()->to('pelayanan')->with('success', 'Data pelayanan berhasil diperbarui.');
  }

  public function delete(int $id)
  {
    $this->pelayananModel->delete($id);
    return redirect()->to('pelayanan')->with('success', 'Data pelayanan berhasil dihapus.');
  }
}
