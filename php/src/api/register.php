<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use BatoiBook\Services\DBService;

header('Content-Type: application/json');

$secretKey = 'clau_secreta';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password) && !empty($data->nick)) {
    try {
        $db = DBService::connect();

        // Verificar si el email ya existe
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data->email]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already registered']);
            exit;
        }

        // Insertar el nuevo usuario
        $stmt = $db->prepare("INSERT INTO users (email, nick, password) VALUES (?, ?, ?)");
        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);
        $stmt->execute([$data->email, $data->nick, $hashedPassword]);

        // Confirmar que el usuario fue insertado
        if ($stmt->rowCount() === 0) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to insert user into database']);
            exit;
        }

        // Depuración: Usuario agregado con éxito
        echo json_encode(['debug' => 'User inserted successfully']);

        // Crear payload para el token JWT
        $payload = [
            'iss' => 'batoiBooks',
            'iat' => time(),
            'exp' => time() + (60 * 60), // Token válido por 1 hora
            'sub' => $data->email
        ];

        // Depuración: Antes de generar el token
        echo json_encode(['debug' => 'About to generate token']);

        // Generar el token JWT
        try {
            $jwt = JWT::encode($payload, $secretKey, 'HS256');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error generating token',
                'details' => $e->getMessage()
            ]);
            exit;
        }

        // Depuración: Token generado con éxito
        echo json_encode(['debug' => 'Token generated successfully', 'token' => $jwt]);

        // Responder con el token
        echo json_encode(['message' => 'User registered successfully', 'token' => $jwt]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
}
