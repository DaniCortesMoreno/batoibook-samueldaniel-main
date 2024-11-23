<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use BatoiBook\Services\DBService;

try {
    // Conectar a la base de datos
    $db = DBService::connect();

    // Seleccionar todas las contraseñas existentes
    $stmt = $db->query("SELECT id, password FROM users");
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        // Saltar si la contraseña ya está encriptada
        if (password_verify('test', $user['password'])) {
            continue; // Ya está encriptada, no actualizar
        }

        // Encriptar la contraseña
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $user['id']]);
    }

    echo "Contraseñas actualizadas correctamente.";
} catch (PDOException $e) {
    echo "Error actualizando contraseñas: " . $e->getMessage();
}
