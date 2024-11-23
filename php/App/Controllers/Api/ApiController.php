<?php
namespace BatoiBook\Controllers\Api;

use BatoiBook\Services\DBService;
use \PDO;

class ApiController {
    protected PDO $db;
    public function __construct() {
        $this->db = DBService::connect();
    }

    // Métode para enviar respostes
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Métode per enviar errors en format JSON
    protected function errorResponse($message, $statusCode = 400) {
        $this->jsonResponse(['error' => $message], $statusCode);
    }
}