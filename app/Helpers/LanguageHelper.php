<?php

namespace App\Helpers;

class LanguageHelper
{
    public static function getLanguages()
    {
        return [
            'en' => ['name' => 'English', 'flag' => '🇺🇸'],
            'ar' => ['name' => 'Arabic', 'flag' => '🇱🇧'], // Using Lebanon flag for 'ar' context
            'hy' => ['name' => 'Armenian', 'flag' => '🇦🇲'],
            'fr' => ['name' => 'French', 'flag' => '🇫🇷'],
        ];
    }
}
