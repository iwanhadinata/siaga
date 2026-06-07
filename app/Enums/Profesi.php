<?php

namespace App\Enums;

enum Profesi: string
{
  case PNS              = 'PNS';
  case TNI_POLRI        = 'TNI / Polri';
  case BUMN_BUMD        = 'Pegawai BUMN / BUMD';
  case KARYAWAN_SWASTA  = 'Karyawan Swasta';
  case WIRASWASTA       = 'Wiraswasta / Pengusaha';
  case PROFESIONAL      = 'Profesional / Freelance';
  case PELAJAR          = 'Pelajar / Mahasiswa';
  case MENGURUS_RT      = 'Mengurus Rumah Tangga';
  case PENSIUNAN        = 'Pensiunan';
  case BELUM_BEKERJA    = 'Belum / Tidak Bekerja';
  case LAINNYA          = 'Lainnya';

  public function label(): string
  {
    return $this->value;
  }
}
