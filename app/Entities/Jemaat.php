<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Enums\JenisKelamin;

class Jemaat extends Entity
{
    protected $datamap = [];

    // Casting tipe data secara otomatis
    protected $casts   = [
        'id'            => 'integer',
        'tanggal_lahir' => 'datetime',
        'audit_trail'   => 'json-array', // Otomatis handle kolom JSON
    ];

    // Mutator untuk memastikan input Enum Jenis Kelamin valid
    public function setJenisKelamin(JenisKelamin|string $jk)
    {
        $this->attributes['jenis_kelamin'] = $jk instanceof JenisKelamin ? $jk->value : $jk;
        return $this;
    }
}
