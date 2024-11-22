<?php
include 'config.php'; // Asegúrate de tener la conexión a la base de datos en este archivo

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
    } else {
        echo json_encode(["error" => "Método no soportado."]);
    }
} else {
    echo json_encode(["error" => "Parámetros inválidos."]);
}





?>
