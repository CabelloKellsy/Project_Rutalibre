<?php

header('Content-Type: application/json');
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $sql = "SELECT * FROM usuarios";
            $result = $conn->query($sql);
            $usuarios = $result->fetchAll();
            echo json_encode($usuarios);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO usuarios (nombre, apellido, email, password, fecha_creacion, tipo_usuario) VALUES (:nombre, :apellido, :email, :password, :fecha_creacion, :tipo_usuario)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Usuario creado"));
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, email = :email, password = :password, fecha_creacion = :fecha_creacion, tipo_usuario = :tipo_usuario WHERE id_usuario = :id_usuario";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Usuario actualizado"));
            break;

        case 'DELETE':
            $id_usuario = $_GET['id_usuario'];
            $sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            echo json_encode(array("message" => "Usuario eliminado"));
            break;

        default:
            echo json_encode(array("error" => "Método no permitido"));
            break;
    }
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error en la operación: " . $e->getMessage()));
}

$conn = null;
?>

