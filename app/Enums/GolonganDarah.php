<?php

namespace App\Enums;

enum GolonganDarah: string
{
  case A  = 'A';
  case B  = 'B';
  case AB = 'AB';
  case O  = 'O';
  case TIDAK_TAHU = 'Tidak Tahu';

  public function label(): string
  {
    return $this->value;
  }
}
