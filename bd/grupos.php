<?php

header('Content-Type: application/json');
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $sql = "SELECT * FROM grupos";
            $result = $conn->query($sql);
            $grupos = $result->fetchAll();
            echo json_encode($grupos);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO grupos (id_grupo, nombre_grupo, integrantes, estado, descripcion, viajes_id_viajes) VALUES (:id_grupo, :nombre_grupo, :integrantes, :estado, :descripcion, :viajes_id_viajes)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Grupo creado"));
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE grupos SET nombre_grupo = :nombre_grupo, integrantes = :integrantes, estado = :estado, descripcion = :descripcion, viajes_id_viajes = :viajes_id_viajes WHERE id_grupo = :id_grupo";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Grupo actualizado"));
            break;

        case 'DELETE':
            $id_grupo = $_GET['id_grupo'];
            $sql = "DELETE FROM grupos WHERE id_grupo = :id_grupo";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_grupo', $id_grupo);
            $stmt->execute();
            echo json_encode(array("message" => "Grupo eliminado"));
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

