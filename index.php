<?php

const SUCCESS = true;
const FAIL = false;
const HTTP_CODE_OK = 200;
const HTTP_CODE_BAD_REQUEST = 500;
const HTTP_CODE_NOT_FOUND = 404;
const DEFAULT_EXCEPTION_CODE = 1;

try {
    $response = [];
    
    if($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception(
            "Only route POST is permision",
            HTTP_CODE_BAD_REQUEST
        );
    }

    if($_SERVER['REQUEST_URI'] !== "/user") {
        throw new Exception(
            "This Route is not Defined",
            HTTP_CODE_NOT_FOUND
        );
    }
    $body = json_decode(file_get_contents("php://input"));

    $connection = new PDO(
        "mysql:host=localhost;dbname=users",
        "root",
        "positivo"
    );

    $sql = "Insert INTO users (name, lastname, age) VALUES (:name, :lastname, :age)";
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ":name" => $body->name,
        ":lastname" => $body->lastnamename,
        ":age" => $body->age
    ]);

    http_response_code(HTTP_CODE_OK);
    $response["success"] = SUCCESS;

}catch ( Exception $e) {
    $exceptionCode = $e->getCode();
    http_response_code(
        $exceptionCode == DEFAULT_EXCEPTION_CODE ?
        HTTP_CODE_BAD_REQUEST : $exceptionCode
    );
        
    $response["success"] = FAIL;
    $response["message"] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode( $response);