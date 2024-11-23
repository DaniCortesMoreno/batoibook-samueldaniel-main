<?php
namespace BatoiBook\Controllers\Api;
use \PDO;
use \PDOException;
use BatoiBook\Services\DBService;
use BatoiBook\Controllers\Api\ApiController;
use BatoiBook\Controllers\Models\Module;
use BatoiBook\Controllers\Models\User;

class UserController extends ApiController
{
    protected PDO $db;
    public function __construct()
    {
        $this->db = DBService::connect();
    }

    public function getAll(): void
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);

        $this->jsonResponse($stmt->fetchAll());
    }

    public function getOne(string $id): void
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $record = $stmt->fetch();

        if ($record) {
            $this->jsonResponse($record);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }

    public function create(array $data): void
    {
        try {
            // Preparar la consulta para insertar un nuevo usuario
            $stmt = $this->db->prepare("INSERT INTO users (email, nick, password) VALUES (:email, :nick, :password)");
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':nick', $data['nick']);
            $stmt->bindParam(':password', $data['password']); // Se recomienda usar un hash aquí
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el ID del usuario recién creado
            $lastInsertId = $this->db->lastInsertId();
    
            // Responder con éxito y el ID del nuevo usuario
            $this->jsonResponse([
                "success" => true,
                "message" => "Usuario creado con éxito",
                "userId" => $lastInsertId
            ]);
        } catch (PDOException $e) {
            // Manejo de errores
            $this->errorResponse("Failed to create record: " . $e->getMessage());
        }
    }

    public function update(string $id, array $data): void
    {
        try {
            //TODO: Implementar actualització
            $stmt = $this->db->prepare("UPDATE users SET id = :id, email = :email, nick = :nick, password = :password WHERE id = :id");
            
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':nick', $data['nick']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':password', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->jsonResponse(["message" => "Record updated successfully"]);
            } else {
                $this->errorResponse("Record not found", 404);
            }
        } catch (PDOException $e) {
            $this->errorResponse("Failed to update record: " . $e->getMessage());
        }
    }

    public function delete(string $id): void
    {
        //TODO: Implementar eliminació
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->jsonResponse(["message" => "Record deleted successfully"]);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }
}