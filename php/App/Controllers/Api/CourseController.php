<?php
namespace BatoiBook\Controllers\Api;
use \PDO;
use \PDOException;
use BatoiBook\Controllers\Models\Course;
use BatoiBook\Services\DBService;
use BatoiBook\Controllers\Api\ApiController;

class CourseController extends ApiController
{
    protected PDO $db;
    public function __construct()
    {
        $this->db = DBService::connect();
    }

    public function getAll(): void
    {
        $stmt = $this->db->prepare("SELECT * FROM courses");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Course::class);

        $this->jsonResponse($stmt->fetchAll());
    }

    public function getOne(int $id): void
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $id);
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
            $stmt = $this->db->prepare("INSERT INTO courses (id, course, familyId, vliteral, cliteral) 
            VALUES (id:, :course, :idFamily, :vliteral, :cliteral)");
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':course', $data['course']);
            $stmt->bindParam(':familyId', $data['familyId']);
            $stmt->bindParam(':vliteral', $data['vliteral']);
            $stmt->bindParam(':cliteral', $data['cliteral']);
            $stmt->execute();
            return $this->db->lastInsertId();
         } catch (PDOException $e) {
            $this->errorResponse("Failed to create record: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            //TODO: Implementar actualització
            $stmt = $this->db->prepare("UPDATE courses SET course = :course, familyId = :familyId, vliteral = :vliteral, cliteral = :cliteral WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':course', $data['course']);
            $stmt->bindParam(':familyId', $data['familyId']);
            $stmt->bindParam(':vliteral', $data['vliteral']);
            $stmt->bindParam(':cliteral', $data['cliteral']);

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

    public function delete(int $id): void
    {
        //TODO: Implementar eliminació
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->jsonResponse(["message" => "Record deleted successfully"]);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }
}