<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Util;

class ClearString
{
    public static function execute($string) {
        $string = str_replace('/', '', $string);
        $string = str_replace('\\', '', $string);
        return $string;
    }  
}