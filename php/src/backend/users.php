<?php
namespace BatoiBook\Controllers\Api;
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';


use BatoiBook\Controllers\Api\UserController;

//header("Content-Type: application/json");


$controller = new UserController();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (string)$_GET['id'] : null;

switch ($method) {
    case 'GET':
        if (isset($id)) {
            $controller->getOne($id);
        } else {
            $controller->getAll ();
       }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data) {
            $controller->create($data);
        } else {
            echo json_encode(["error" => "Invalid data"]);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($_GET['id']) && $data) {
            $success = $controller->update ($id, $data);
            echo json_encode(["message" => $success ? "Field updated successfully" : "Book not found"]);
        } else {
            echo json_encode(["error" => "Invalid data or ID"]);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $controller->delete ($id);
        } else {
            echo json_encode(["error" => "ID not provided"]);
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}