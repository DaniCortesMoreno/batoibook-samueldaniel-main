<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once '/home/apiDaniCortes/batoibook-samueldaniel-main/php/App/Services/AuthService.php';

use BatoiBook\Services\AuthService;

// Configurar las cabeceras para la respuesta JSON
header('Content-Type: application/json');

// Depuración inicial
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Si no es JSON, intenta capturar datos enviados por el formulario HTML
if (!$data) {
    $data = [
        'email' => $_POST['email'] ?? null,
        'password' => $_POST['password'] ?? null,
    ];
}

// Mensaje de depuración
echo json_encode([
    'debug' => [
        'Inicio de login.php',
        'Datos en bruto recibidos' => $rawData,
        'Datos decodificados' => $data
    ]
]);

// Validar datos
if (!$data['email'] || !$data['password']) {
    echo json_encode(['error' => 'Faltan campos obligatorios']);
    http_response_code(400);
    exit;
}

// Autenticar al usuario
try {
    $authService = new AuthService();
    $result = $authService->login($data['email'], $data['password']);
    echo json_encode(['debug' => 'Resultado de autenticación', 'result' => $result]);

    if ($result['success']) {
        echo json_encode([
            'message' => 'Login exitoso',
            'token' => $result['token']
        ]);
    } else {
        echo json_encode([
            'error' => $result['message']
        ]);
        http_response_code(401); // Unauthorized
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Excepción atrapada', 'message' => $e->getMessage()]);
    http_response_code(500);
}
