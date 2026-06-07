<?php

namespace App\Enums;

enum StatusPernikahan: string
{
    case BELUM_MENIKAH = 'belum_menikah';
    case MENIKAH       = 'menikah';
    case CERAI_HIDUP   = 'cerai_hidup';
    case CERAI_MATI    = 'cerai_mati';

    public function label(): string
    {
        return match($this) {
            self::BELUM_MENIKAH => 'Belum Menikah',
            self::MENIKAH       => 'Menikah',
            self::CERAI_HIDUP   => 'Cerai Hidup',
            self::CERAI_MATI    => 'Cerai Mati',
        };
    }
}