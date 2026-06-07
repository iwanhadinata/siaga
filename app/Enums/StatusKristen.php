<?php

namespace App\Enums;

enum StatusKristen: string
{
  case KRISTEN  = 'Kristen';
  case BELUM    = 'Belum';

  public function label(): string
  {
    return $this->value;
  }
}
