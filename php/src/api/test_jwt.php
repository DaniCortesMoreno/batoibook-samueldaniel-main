<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;

$secretKey = 'clau_secreta';
$payload = [
    'iss' => 'batoiBooks',
    'iat' => time(),
    'exp' => time() + (60 * 60), // Token válido por 1 hora
    'sub' => 'test@ejemplo.com'
];

try {
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    echo "Token generado: " . $jwt;
} catch (Exception $e) {
    echo "Error generando el token: " . $e->getMessage();
}
