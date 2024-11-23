<?php
header('Content-Type: application/json');
include 'connection.php';

class ActividadesAPI
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    private function validateData($data)
    {
        $errors = [];

        if (empty($data['nombre_actividad'])) {
            $errors[] = "El nombre de la actividad es requerido";
        }

        if (empty($data['descripcion'])) {
            $errors[] = "La descripción de la actividad es requerida";
        }

        if (empty($data['fecha_inicio'])) {
            $errors[] = "La fecha de inicio es requerida";
        }

        if (empty($data['fecha_fin'])) {
            $errors[] = "La fecha de fin es requerida";
        }

        if (empty($data['viajes_id_viajes']) || !is_numeric($data['viajes_id_viajes'])) {
            $errors[] = "El ID del viaje es requerido y debe ser válido";
        }

        return $errors;
    }

    public function getActividadesByViaje($viajeId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    a.id_actividad,
                    a.nombre_actividad,
                    a.descripcion,
                    a.fecha_inicio,
                    a.fecha_fin
                FROM 
                    actividades a
                INNER JOIN 
                    viajes v ON a.viajes_id_viajes = v.id_viaje
                WHERE 
                    v.id_viaje = :viaje_id
                ORDER BY 
                    a.fecha_inicio ASC
            ");
            $stmt->execute([':viaje_id' => $viajeId]);
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($actividades)) {
                return $this->sendResponse([
                    "message" => "El viaje no tiene actividades",
                    "actividades" => []
                ]);
            }

            return $this->sendResponse([
                "message" => "Actividades encontradas",
                "actividades" => $actividades
            ]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener actividades: " . $e->getMessage()], 500);
        }
    }

    public function getActividadesByUsuario($userId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    a.nombre_actividad,
                    a.descripcion,
                    a.fecha_inicio,
                    a.fecha_fin,
                    v.nombre_viaje
                FROM 
                    actividades a
                INNER JOIN 
                    viajes v ON a.viajes_id_viajes = v.id_viaje
                WHERE 
                    v.id_usuario = :user_id
                ORDER BY 
                    a.fecha_inicio ASC
            ");
            $stmt->execute([':user_id' => $userId]);
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener actividades: " . $e->getMessage()], 500);
        }
    }

    public function createActividad($data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            $stmt = $this->conn->prepare("
                INSERT INTO actividades (
                    nombre_actividad, 
                    descripcion, 
                    fecha_inicio, 
                    fecha_fin, 
                    viajes_id_viajes
                ) VALUES (
                    :nombre_actividad,
                    :descripcion,
                    :fecha_inicio,
                    :fecha_fin,
                    :viajes_id_viajes
                )
            ");

            $stmt->execute([
                ':nombre_actividad' => $data['nombre_actividad'],
                ':descripcion' => $data['descripcion'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_fin' => $data['fecha_fin'],
                ':viajes_id_viajes' => $data['viajes_id_viajes']
            ]);

            return $this->sendResponse([
                "message" => "Actividad creada exitosamente",
                "id" => $this->conn->lastInsertId()
            ], 201);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al crear actividad: " . $e->getMessage()], 500);
        }
    }

    public function updateActividad($id, $data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            $stmt = $this->conn->prepare("
                UPDATE actividades
                SET 
                    nombre_actividad = :nombre_actividad,
                    descripcion = :descripcion,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    viajes_id_viajes = :viajes_id_viajes
                WHERE id_actividad = :id_actividad
            ");

            $stmt->execute([
                ':id_actividad' => $id,
                ':nombre_actividad' => $data['nombre_actividad'],
                ':descripcion' => $data['descripcion'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_fin' => $data['fecha_fin'],
                ':viajes_id_viajes' => $data['viajes_id_viajes']
            ]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Actividad no encontrada"], 404);
            }

            return $this->sendResponse(["message" => "Actividad actualizada exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al actualizar actividad: " . $e->getMessage()], 500);
        }
    }

    public function deleteActividad($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM actividades WHERE id_actividad = :id_actividad");
            $stmt->execute([':id_actividad' => $id]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Actividad no encontrada"], 404);
            }

            return $this->sendResponse(["message" => "Actividad eliminada exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al eliminar actividad: " . $e->getMessage()], 500);
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        try {
            switch ($method) {
                case 'GET':
                    if (isset($_GET['viaje_id'])) {
                        $this->getActividadesByViaje($_GET['viaje_id']);
                    } else if (isset($_GET['user_id'])) {
                        $this->getActividadesByUsuario($_GET['user_id']);
                    } else {
                        $this->sendResponse(["error" => "Parámetros no especificados"], 400);
                    }
                    break;

                case 'POST':
                    $this->createActividad($_POST);
                    break;

                case 'PUT':
                    $data = json_decode(file_get_contents('php://input'), true);
                    $id = isset($_GET['id_actividad']) ? $_GET['id_actividad'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de actividad no proporcionado"], 400);
                    }
                    $this->updateActividad($id, $data);
                    break;

                case 'DELETE':
                    $id = isset($_GET['id_actividad']) ? $_GET['id_actividad'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de actividad no proporcionado"], 400);
                    }
                    $this->deleteActividad($id);
                    break;

                default:
                    $this->sendResponse(["error" => "Método no permitido"], 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(["error" => "Error interno del servidor: " . $e->getMessage()], 500);
        }
    }
}

$api = new ActividadesAPI($conn);
$api->handleRequest();
