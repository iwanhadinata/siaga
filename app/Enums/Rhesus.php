<?php

namespace App\Enums;

enum Rhesus: string
{
  case POSITIF    = 'Positif';
  case NEGATIF    = 'Negatif';
  case TIDAK_TAHU = 'Tidak Tahu';

  public function label(): string
  {
    return $this->value;
  }
}
