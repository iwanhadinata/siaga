<?php

namespace App\Enums;

enum Pendidikan: string
{
  case SD  = 'SD';
  case SMP = 'SMP';
  case SMA = 'SMA';
  case D1  = 'D1';
  case D2  = 'D2';
  case D3  = 'D3';
  case S1  = 'S1';
  case S2  = 'S2';
  case S3  = 'S3';

  public function label(): string
  {
    return match ($this) {
      self::SD  => 'SD / Sederajat',
      self::SMP => 'SMP / Sederajat',
      self::SMA => 'SMA / Sederajat',
      self::D1  => 'Diploma 1 (D1)',
      self::D2  => 'Diploma 2 (D2)',
      self::D3  => 'Diploma 3 (D3)',
      self::S1  => 'Sarjana (S1)',
      self::S2  => 'Magister (S2)',
      self::S3  => 'Doktor (S3)',
    };
  }
}
