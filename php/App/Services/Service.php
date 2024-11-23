<?php

namespace BatoiBook\Services;

class Service {
    public static function loadView($view, $data = []) {
        try {
            echo "Intentando cargar la vista: $view<br>";
            
            // Construir la ruta a la vista
            $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.view.php';
            echo "Ruta construida: $viewPath<br>";

            // Verificar si el archivo existe
            if (file_exists($viewPath)) {
                // Extraer las variables para usarlas en la vista
                extract($data);

                // Incluir la vista
                require $viewPath;
            } else {
                echo "Error: La vista $view no existe en la ruta $viewPath<br>";
            }
        } catch (\Exception $e) {
            echo "Error al cargar la vista: " . $e->getMessage();
        }
    }
}
