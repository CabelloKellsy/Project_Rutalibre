<?php
header('Content-Type: application/json');
include 'connection.php';

class GruposAPI
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

        if (empty($data['nombre_grupo'])) {
            $errors[] = "El nombre del grupo es requerido";
        }

        if (!is_numeric($data['integrantes']) || $data['integrantes'] < 0) {
            $errors[] = "La cantidad de integrantes debe ser un número válido";
        }

        if (!in_array($data['estado'], ['Activo', 'Eliminado'])) {
            $errors[] = "Estado inválido";
        }

        if (empty($data['descripcion'])) {
            $errors[] = "La descripcion del grupo es requerida";
        }

        if (empty($data['viajes_id_viajes']) || !is_numeric($data['viajes_id_viajes'])) {
            $errors[] = "El ID del viaje es requerido y debe ser válido";
        }

        return $errors;
    }

    public function getViajes()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id_viajes, nombre_viaje FROM viajes ORDER BY id_viajes ASC");
            $stmt->execute();
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener viajes: " . $e->getMessage()], 500);
        }
    }

    public function getGruposByViaje($viajeId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM grupos 
                WHERE viajes_id_viajes = :viaje_id 
                ORDER BY id_grupo ASC
            ");
            $stmt->execute([':viaje_id' => $viajeId]);
            $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($grupos)) {
                return $this->sendResponse([
                    "message" => "El viaje no tiene grupos",
                    "grupos" => []
                ]);
            }

            return $this->sendResponse([
                "message" => "Grupos encontrados",
                "grupos" => $grupos
            ]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener grupos: " . $e->getMessage()], 500);
        }
    }

    public function createGrupo($data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            // Verificar si el viaje existe
            $stmt = $this->conn->prepare("SELECT id_viajes FROM viajes WHERE id_viajes = :viaje_id");
            $stmt->execute([':viaje_id' => $data['viajes_id_viajes']]);
            if (!$stmt->fetch()) {
                return $this->sendResponse(["error" => "El viaje especificado no existe"], 404);
            }

            $stmt = $this->conn->prepare("
                INSERT INTO grupos (nombre_grupo, integrantes, estado, descripcion, viajes_id_viajes)
                VALUES (:nombre_grupo, :integrantes, :estado, :descripcion, :viajes_id_viajes)
            ");

            $stmt->execute([
                ':nombre_grupo' => $data['nombre_grupo'],
                ':integrantes' => $data['integrantes'],
                ':estado' => $data['estado'],
                ':descripcion' => $data['descripcion'],
                ':viajes_id_viajes' => $data['viajes_id_viajes']
            ]);

            return $this->sendResponse([
                "message" => "Grupo creado exitosamente",
                "id" => $this->conn->lastInsertId()
            ], 201);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al crear grupo: " . $e->getMessage()], 500);
        }
    }

    public function updateGrupo($id, $data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            $stmt = $this->conn->prepare("
                UPDATE grupos
                SET nombre_grupo = :nombre_grupo,
                    integrantes = :integrantes,
                    estado = :estado,
                    descripcion = :descripcion,
                    viajes_id_viajes = :viajes_id_viajes
                WHERE id_grupo = :id_grupo
            ");

            $stmt->execute([
                ':id_grupo' => $id,
                ':nombre_grupo' => $data['nombre_grupo'],
                ':integrantes' => $data['integrantes'],
                ':estado' => $data['estado'],
                ':descripcion' => $data['descripcion'],
                ':viajes_id_viajes' => $data['viajes_id_viajes']
            ]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Grupo no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Grupo actualizado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al actualizar grupo: " . $e->getMessage()], 500);
        }
    }

    public function deleteGrupo($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM grupos WHERE id_grupo = :id_grupo");
            $stmt->execute([':id_grupo' => $id]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Grupo no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Grupo eliminado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al eliminar grupo: " . $e->getMessage()], 500);
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        try {
            switch ($method) {
                case 'GET':
                    if ($action === 'viajes') {
                        $this->getViajes();
                    } else if (isset($_GET['viaje_id'])) {
                        $this->getGruposByViaje($_GET['viaje_id']);
                    } else {
                        $this->sendResponse(["error" => "Acción no especificada"], 400);
                    }
                    break;

                case 'POST':
                    $this->createGrupo($_POST);
                    break;

                case 'PUT':
                    $data = json_decode(file_get_contents('php://input'), true);
                    $id = isset($_GET['id_grupo']) ? $_GET['id_grupo'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de grupo no proporcionado"], 400);
                    }
                    $this->updateGrupo($id, $data);
                    break;

                case 'DELETE':
                    $id = isset($_GET['id_grupo']) ? $_GET['id_grupo'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de grupo no proporcionado"], 400);
                    }
                    $this->deleteGrupo($id);
                    break;

                default:
                    $this->sendResponse(["error" => "Método no permitido"], 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(["error" => "Error interno del servidor: " . $e->getMessage()], 500);
        }
    }
}

$api = new GruposAPI($conn);
$api->handleRequest();
