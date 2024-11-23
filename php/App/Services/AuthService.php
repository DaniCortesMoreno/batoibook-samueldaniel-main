<?php

namespace BatoiBook\Services;

use Exception;

class AuthService {
    public function login($email, $password) {
        // Simulación de depuración
        $debug = [];
        $debug[] = "Comenzando login para: $email";

        // Conectar a la base de datos
        $db = new \PDO("mysql:host=localhost;dbname=batoiBooks;charset=utf8", "apiDaniCortes", "1234");
        
        // Consultar usuario
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $debug[] = "Autenticación exitosa para: $email";

            // Generar token (puedes usar Firebase JWT)
            $token = base64_encode("$email|securetoken");

            return [
                'success' => true,
                'token' => $token,
                'debug' => $debug
            ];
        } else {
            $debug[] = "Login fallido para $email";
            return [
                'success' => false,
                'message' => 'Correo o contraseña incorrectos',
                'debug' => $debug
            ];
        }
    }
}
