<?php

header('Content-Type: application/json');
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $sql = "SELECT * FROM viajes";
            $result = $conn->query($sql);
            $viajes = $result->fetchAll();
            echo json_encode($viajes);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO viajes (nombre_viaje, fecha_inicio, fecha_final, presupuesto_base, estado) VALUES (:nombre_viaje, :fecha_inicio, :fecha_final, :presupuesto_base, :estado)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Viaje creado"));
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE viajes SET nombre_viaje = :nombre_viaje, fecha_inicio = :fecha_inicio, fecha_final = :fecha_final, presupuesto_base = :presupuesto_base, estado = :estado WHERE id_viaje = :id_viaje";
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            echo json_encode(array("message" => "Viaje actualizado"));
            break;

        case 'DELETE':
            $id_viaje = $_GET['id_viaje'];
            $sql = "DELETE FROM viajes WHERE id_viaje = :id_viaje";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_viaje', $id_viaje);
            $stmt->execute();
            echo json_encode(array("message" => "Viaje eliminado"));
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
