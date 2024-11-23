<?php
session_start();
include '../bd/connection.php'; // Conexión a la base de datos
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "No estás autenticado."]);
    exit;
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userId']) && isset($_GET['method'])) {
    $userId = intval($_GET['userId']);
    $method = $_GET['method'];

    if ($method === 'getProximosViajes') {
        try {
            $stmt = $conn->prepare("
                SELECT
                    v.id_viaje,
                    v.nombre_viaje,
                    v.fecha_inicio,
                    v.fecha_final, 
                    v.presupuesto_base,
                    v.destino,
                    v.estado,
                    v.fecha_creacion
                FROM
                    viajes v
                WHERE
                    v.id_usuario = :userId
                    AND v.fecha_inicio >= CURDATE()
                ORDER BY
                    v.fecha_inicio ASC
            ");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener próximos viajes: " . $e->getMessage()]);
        }
    } elseif ($method === 'getViajesAnteriores') {  // Aquí es donde debes agregar el nuevo método
        try {
            $stmt = $conn->prepare("
                 SELECT
                     v.id_viaje,
                     v.nombre_viaje,
                     v.fecha_inicio,
                     v.fecha_final,
                     v.presupuesto_base,
                     v.destino,
                     v.estado,
                     v.fecha_creacion
                 FROM
                     viajes v
                 WHERE
                     v.id_usuario = :userId
                     AND v.fecha_inicio < CURDATE()
                 ORDER BY
                     v.fecha_inicio DESC
             ");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al obtener viajes anteriores: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Método no soportado."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
    $method = $_POST['method'];

    if ($method === 'updateViaje' && isset($_POST['id_viaje'])) {
        // Lógica para actualizar el viaje
        try {

            $stmt = $conn->prepare("
                UPDATE viajes
                SET nombre_viaje = :nombre_viaje,
                    fecha_inicio = :fecha_inicio,
                    fecha_final = :fecha_final,
                    presupuesto_base = :presupuesto_base,
                    destino = :destino,
                    estado = :estado
                WHERE id_viaje = :id_viaje
            ");
            $data = [
                "id_viaje" => $_POST['id_viaje'],
                "nombre_viaje" => $_POST['nombre_viaje'],
                "fecha_inicio" => $_POST['fecha_inicio'],
                "fecha_final" => $_POST['fecha_final'],
                "presupuesto_base" => $_POST['presupuesto_base'],
                "destino" => $_POST['destino'] ?? null, // Maneja destino vacío
                "estado" => $_POST['estado']
            ];
            
            $stmt->execute($data);

            if ($stmt->rowCount() === 0) {
                echo json_encode(["error" => "Viaje no encontrado"]);
            }
            echo json_encode(["message" => "Viaje actualizado exitosamente", "success" => 1]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al actualizar viaje: " . $e->getMessage()]);
        }
    } elseif ($method === 'deleteViaje' && isset($_POST['id_viaje'])) {
        // Lógica para eliminar el viaje
    } else {
        echo json_encode(["error" => "Parámetros inválidos."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['method'])) {
    $method = $_GET['method'];

    if ($method === 'deleteViaje' && isset($_GET['id_viaje'])) {
        try {
            $id = $_GET['id_viaje'];
            $stmt = $conn->prepare("DELETE FROM viajes WHERE id_viaje = :id_viaje");
            $stmt->execute([':id_viaje' => $id]);

            if ($stmt->rowCount() === 0) {
                echo json_encode(["error" => "Viaje no encontrado"]);
            }
            echo json_encode(["message" => "Viaje eliminado exitosamente", "success" => 1]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error al eliminar viaje: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["error" => "Parámetros inválidos."]);
    }
} else {
    echo json_encode(["error" => "Parámetros inválidos."]);
}
