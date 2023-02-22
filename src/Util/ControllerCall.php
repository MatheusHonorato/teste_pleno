<?php

declare(strict_types=1);

namespace Matheus\TestePleno\Util;

class ControllerCall
{
    public static function generate($url) {
        $url = explode('?', $_SERVER['REQUEST_URI']);
        $url = explode('/', $url[getenv('FIRST_VALUE')]);
    
        $controller = '\Matheus\TestePleno\Controllers\\'.ucfirst(ClearString::execute($url[getenv('CONTROLLER_INDICE')])).'Controller';
    
        $controller = preg_replace('/[0-9]+h/', '', $controller);
    
        return $controller;
      }
}