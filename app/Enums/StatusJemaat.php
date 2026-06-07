<?php

namespace App\Enums;

enum StatusJemaat: string
{
  case AKTIF = 'Aktif';
  case BARU = 'Baru';
  case SIMPATISAN = 'Simpatisan';
  case PINDAH_GEREJA = 'Pindah Gereja';
  case LUAR_KOTA = 'Luar Kota';
  case MENINGGAL = 'Meninggal';

  public function label(): string
  {
    return $this->value;
  }
}
