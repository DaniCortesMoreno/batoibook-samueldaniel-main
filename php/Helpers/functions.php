<?php

namespace BatoiBook\Helpers;

use BatoiBook\Services\Service;

class functions {
    public static function loadView($view, $data = [])
    {
        echo "Cargando vista: $view";
        // Llama al servicio para cargar la vista
        Service::loadView($view, $data);
    }
    
    public static function dd(...$data)
    {
        // Depuración: imprime los datos y detiene la ejecución
        echo "<pre>";
        foreach ($data as $d) {
            var_dump($d);
        }
        echo "</pre>";
        die();
    }
}

