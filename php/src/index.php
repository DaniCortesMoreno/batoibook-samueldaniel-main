<?php
// Ruta del archivo de la vista
$viewPath = __DIR__ . '/../App/Views/login.view.php';

// Verificar si el archivo existe
if (file_exists($viewPath)) {
    $title = 'Iniciar sesión';
    $action = '/api/login.php'; // Ruta donde enviará los datos el formulario

    include $viewPath;
} else {
    echo "Error: La vista no se encuentra en la ruta $viewPath";
}
