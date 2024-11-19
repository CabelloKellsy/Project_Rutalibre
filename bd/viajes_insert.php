<?php
header('Content-Type: application/json');
include 'connection.php';

class ViajesAPI
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

        if (empty($data['nombre_viaje'])) {
            $errors[] = "El nombre del viaje es requerido";
        }

        if (empty($data['fecha_inicio'])) {
            $errors[] = "La fecha de inicio es requerida";
        }

        if (empty($data['fecha_final'])) {
            $errors[] = "La fecha final es requerida";
        }

        if (!is_numeric($data['presupuesto_base']) || $data['presupuesto_base'] < 0) {
            $errors[] = "El presupuesto base debe ser un número positivo";
        }

        if (!in_array($data['estado'], ['Planificado', 'En Curso', 'Finalizado'])) {
            $errors[] = "Estado inválido";
        }

        return $errors;
    }

    public function getViajes()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM viajes ORDER BY id_viaje ASC");
            $stmt->execute();
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener viajes: " . $e->getMessage()], 500);
        }
    }

    public function createViaje($data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            $stmt = $this->conn->prepare("
                INSERT INTO viajes (nombre_viaje, fecha_inicio, fecha_final, presupuesto_base, estado)
                VALUES (:nombre_viaje, :fecha_inicio, :fecha_final, :presupuesto_base, :estado)
            ");

            $stmt->execute([
                ':nombre_viaje' => $data['nombre_viaje'],
                ':fecha_inicio' => $data['fecha_inicio'],
                ':fecha_final' => $data['fecha_final'],
                ':presupuesto_base' => $data['presupuesto_base'],
                ':estado' => $data['estado']
            ]);

            return $this->sendResponse(["message" => "Viaje creado exitosamente", "id" => $this->conn->lastInsertId()], 201);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al crear viaje: " . $e->getMessage()], 500);
        }
    }

    public function updateViaje($id, $data)
    {
        try {
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            $stmt = $this->conn->prepare("
                UPDATE viajes
                SET nombre_viaje = :nombre_viaje,
                    fecha_inicio = :fecha_inicio,
                    fecha_final = :fecha_final,
                    presupuesto_base = :presupuesto_base,
                    estado = :estado
                WHERE id_viaje = :id_viaje
            ");

            $data['id_viaje'] = $id;
            $stmt->execute($data);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Viaje no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Viaje actualizado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al actualizar viaje: " . $e->getMessage()], 500);
        }
    }

    public function deleteViaje($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM viajes WHERE id_viaje = :id_viaje");
            $stmt->execute([':id_viaje' => $id]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Viaje no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Viaje eliminado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al eliminar viaje: " . $e->getMessage()], 500);
        }
    }


    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            switch ($method) {
                case 'GET':
                    $this->getViajes();
                    break;

                case 'POST':
                    $this->createViaje($_POST);
                    break;

                case 'PUT':
                    $data = json_decode(file_get_contents('php://input'), true);
                    $id = isset($_GET['id_viaje']) ? $_GET['id_viaje'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de viaje no proporcionado"], 400);
                    }
                    $this->updateViaje($id, $data);
                    break;

                case 'DELETE':
                    $id = isset($_GET['id_viaje']) ? $_GET['id_viaje'] : null;
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de viaje no proporcionado"], 400);
                    }
                    $this->deleteViaje($id);
                    break;

                default:
                    $this->sendResponse(["error" => "Método no permitido"], 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(["error" => "Error interno del servidor: " . $e->getMessage()], 500);
        }
    }
}

// Uso de la API
$api = new ViajesAPI($conn);
$api->handleRequest();

