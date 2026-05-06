<?php

namespace App\Services;

class CodeService
{
    private static $codes = [
        'mangerCbon','courir10km','yoga30min','meditation15min'
    ];
    public static function verifierCode(string $code): bool
    {
        foreach (self::$codes as $validCode) {
            if ($code === $validCode) {
                return true;
            }
        }
        return false;
    }


}
