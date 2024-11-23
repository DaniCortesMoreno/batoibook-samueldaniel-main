<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use BatoiBook\Services\DBService;

// Clau secreta per generar el token (és important mantenir-la segura)
$secretKey = 'clau_secreta';
header('Content-Type: application/json');
// Llegeix les dades de la petició
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {

     $db = DBService::connect();  
     //TODO: Implementar consulta per recuperar l'usuari
     $user = $stmt->fetch();
     if ($user) {
         if (password_verify($data->password,$user->password)) {
             $payload = [
                  "iss" => "http://localhost", // Issuer
                  "aud" => "http://localhost", // Audience
                  "iat" => time(),             // Issued at
                  "exp" => time() + 3600,      // Expira en una hora
                  "userId" => $userId
              ];
             $jwt = JWT::encode($payload, $secretKey, 'HS256');    
             echo json_encode(["success" => true, "token" => $jwt]);
         } else {
             echo json_encode(["success" => false, "message" => "Contrasenya incorrecta"]);
         }
     } else {
         echo json_encode(["success" => false, "message" => "Usuari no trobat"]);
     }

} else {
    echo json_encode(["success" => false, "message" => "Falten dades"]);
}