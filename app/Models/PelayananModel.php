<?php

namespace App\Models;

use CodeIgniter\Model;

class PelayananModel extends Model
{
  protected $table            = 'tbl_pelayanan';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object'; 
  protected $useSoftDeletes   = true;

  protected $allowedFields    = [
    'nama_pelayanan',
    'deskripsi_pelayanan'
  ];

  protected $useTimestamps    = true;

  /**
   * Ambil data pelayanan aktif untuk dropdown/checkbox form
   */
  public function getActivePelayanan(): array
  {
    return $this->select('id, nama_pelayanan')
      ->orderBy('nama_pelayanan', 'ASC')
      ->asArray() // <--- Paksa menjadi array khusus untuk query ini
      ->findAll();
  }
}
