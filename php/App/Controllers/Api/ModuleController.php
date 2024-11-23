<?php
namespace BatoiBook\Controllers\Api;
use \PDO;
use \PDOException;
use BatoiBook\Services\DBService;
use BatoiBook\Controllers\Api\ApiController;
use BatoiBook\Controllers\Models\Module;

class ModuleController extends ApiController
{
    protected PDO $db;
    public function __construct()
    {
        $this->db = DBService::connect();
    }

    public function getAll(): void
    {
        $stmt = $this->db->prepare("SELECT * FROM modules");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Module::class);

        $this->jsonResponse($stmt->fetchAll());
    }

    public function getOne(string $code): void
    {
        $stmt = $this->db->prepare("SELECT * FROM modules WHERE code = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        
        $record = $stmt->fetch();

        if ($record) {
            $this->jsonResponse($record);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }

    public function create(array $data): int
    {
        try {
            //TODO: Implementar inserció
            $stmt = $this->db->prepare("INSERT INTO modules (code,cliteral, vliteral, courseId) VALUES (:code,:cliteral, :vliteral, :courseId)");
            $stmt->bindParam(':code', $data['code']);
            $stmt->bindParam(':cliteral', $data['cliteral']);
            $stmt->bindParam(':vliteral', $data['vliteral']);
            $stmt->bindParam(':courseId', $data['courseId']);
            $stmt->execute();
            return $this->db->lastInsertId();
         } catch (PDOException $e) {
            $this->errorResponse("Failed to create record: " . $e->getMessage());
        }
    }

    public function update(string $id, array $data): void
    {
        try {
            //TODO: Implementar actualització
            $stmt = $this->db->prepare("UPDATE modules SET code = :code, cliteral = :cliteral, vliteral = :vliteral, courseId = :courseId WHERE code = :code");
            
            $stmt->bindParam(':cliteral', $data['cliteral']);
            $stmt->bindParam(':vliteral', $data['vliteral']);
            $stmt->bindParam(':courseId', $data['courseId']);
            $stmt->bindParam(':code', $id);
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

    public function delete(string $code): void
    {
        //TODO: Implementar eliminació
        $stmt = $this->db->prepare("DELETE FROM modules WHERE code = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->jsonResponse(["message" => "Record deleted successfully"]);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }
}