<?php
namespace BatoiBook\Controllers\Api;

use \PDO;
use \PDOException;
use BatoiBook\Controllers\Models\Book;
use BatoiBook\Services\DBService;
use BatoiBook\Controllers\Api\ApiController;

class BookController extends ApiController {

    protected PDO $db;

    public function __construct()
    {
        $this->db = DBService::connect();
    }

    public function getAll(): void
    {
        $stmt = $this->db->prepare("SELECT * FROM books");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Book::class);

        $this->jsonResponse($stmt->fetchAll());
    }

    public function getOne(int $id): void
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
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
            $stmt = $this->db->prepare("INSERT INTO books (userId, moduleCode, publisher, price, pages, status, photo, comments, soldDate) 
            VALUES (:userId, :moduleCode, :publisher, :price, :pages, :status, :photo, :comments, :soldDate)");
            $stmt->bindParam(':userId', $data['userId']);
            $stmt->bindParam(':moduleCode', $data['moduleCode']);
            $stmt->bindParam(':publisher', $data['publisher']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':pages', $data['pages']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':photo', $data['photo']);
            $stmt->bindParam(':comments', $data['comments']);
            $stmt->bindParam(':soldDate', $data['soldDate']);
            $stmt->execute();
            return $this->db->lastInsertId();
         } catch (PDOException $e) {
            $this->errorResponse("Failed to create record: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $stmt = $this->db->prepare("UPDATE books SET userId = :userId, moduleCode = :moduleCode, publisher = :publisher, price = :price, 
            pages = :pages, status = :status, photo = :photo, comments = :comments, soldDate = :soldDate WHERE id = :id");
            
            $stmt->bindParam(':userId', $data['userId']);
            $stmt->bindParam(':moduleCode', $data['moduleCode']);
            $stmt->bindParam(':publisher', $data['publisher']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':pages', $data['pages']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':photo', $data['photo']);
            $stmt->bindParam(':comments', $data['comments']);
            $stmt->bindParam(':soldDate', $data['soldDate']);
            $stmt->bindParam(':id', $id);
            
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
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->jsonResponse(["message" => "Record deleted successfully"]);
        } else {
            $this->errorResponse("Record not found", 404);
        }
    }
    public function getFiltered(?int $userId, ?string $moduleCode): void
{
    try {
        $query = "SELECT * FROM books WHERE 1=1";
        $params = [];

        if ($userId) {
            $query .= " AND userId = :userId";
            $params[':userId'] = $userId;
        }

        if ($moduleCode) {
            $query .= " AND moduleCode = :moduleCode";
            $params[':moduleCode'] = $moduleCode;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Book::class);

        $books = $stmt->fetchAll();

        if ($books) {
            $this->jsonResponse($books);
        } else {
            $this->jsonResponse([]);
        }
    } catch (PDOException $e) {
        $this->errorResponse("Failed to retrieve records: " . $e->getMessage(), 500);
    }
}
}

