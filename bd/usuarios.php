<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'connection.php';

class UsuariosAPI
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

    private function validateUserData($data)
    {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = "El nombre es requerido";
        }

        if (empty($data['apellido'])) {
            $errors[] = "El apellido es requerido";
        }

        if (empty($data['email'])) {
            $errors[] = "El email es requerido";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El email no es válido";
        }

        if (empty($data['password'])) {
            $errors[] = "La contraseña es requerida";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres";
        }

        return $errors;
    }

    public function getUsuarios()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id_usuario, nombre, apellido, email, tipo_usuario, fecha_creacion FROM usuarios ORDER BY id_usuario ASC");
            $stmt->execute();
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener usuarios: " . $e->getMessage()], 500);
        }
    }

    public function createUsuario($data)
    {
        try {
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            // Check if email already exists
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $checkStmt->execute([':email' => $data['email']]);
            if ($checkStmt->fetchColumn() > 0) {
                return $this->sendResponse(["error" => "El email ya está registrado"], 400);
            }

            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("
                INSERT INTO usuarios (nombre, apellido, email, password, fecha_creacion, tipo_usuario)
                VALUES (:nombre, :apellido, :email, :password, NOW(), :tipo_usuario)
            ");

            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':email' => $data['email'],
                ':password' => $hashedPassword,
                ':tipo_usuario' => $data['tipo_usuario'] ?? 'particular'
            ]);

            return $this->sendResponse([
                "message" => "Usuario creado exitosamente",
                "id" => $this->conn->lastInsertId()
            ], 201);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al crear usuario: " . $e->getMessage()], 500);
        }
    }

    public function updateUsuario($id, $data)
    {
        try {
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                return $this->sendResponse(["errors" => $errors], 400);
            }

            // Hash password if provided
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("
                UPDATE usuarios
                SET nombre = :nombre,
                    apellido = :apellido,
                    email = :email,
                    password = :password,
                    tipo_usuario = :tipo_usuario
                WHERE id_usuario = :id
            ");

            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':email' => $data['email'],
                ':password' => $hashedPassword,
                ':tipo_usuario' => $data['tipo_usuario'] ?? 'particular',
                ':id' => $id
            ]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Usuario no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Usuario actualizado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al actualizar usuario: " . $e->getMessage()], 500);
        }
    }

    public function deleteUsuario($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() === 0) {
                return $this->sendResponse(["error" => "Usuario no encontrado"], 404);
            }

            return $this->sendResponse(["message" => "Usuario eliminado exitosamente"]);
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al eliminar usuario: " . $e->getMessage()], 500);
        }
    }

    public function getViajesDeUsuario($userId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT v.* 
                FROM viajes v
                JOIN usuarios_viaje uv ON v.id_viaje = uv.id_viajes
                WHERE uv.id_usuarios = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener viajes del usuario: " . $e->getMessage()], 500);
        }
    }

    public function getGruposDeUsuario($userId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT g.* 
                FROM grupos g
                JOIN usuarios_grupos ug ON g.id_grupo = ug.grupos_id
                WHERE ug.usuarios_id = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            return $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return $this->sendResponse(["error" => "Error al obtener grupos del usuario: " . $e->getMessage()], 500);
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        try {
            // Extrayendo el último segmento de la ruta
            $pathParts = explode('/', $path);
            $lastPart = end($pathParts);

            switch ($method) {
                case 'GET':
                    if ($lastPart === 'usuarios' || $lastPart === 'usuarios.php') { // Manejar ambas rutas
                        $this->getUsuarios();
                    } elseif (strpos($lastPart, 'viajes') !== false) {
                        $userId = filter_input(INPUT_GET, 'usuario_id', FILTER_VALIDATE_INT);
                        if ($userId) {
                            $this->getViajesDeUsuario($userId);
                        }
                    } elseif (strpos($lastPart, 'grupos') !== false) {
                        $userId = filter_input(INPUT_GET, 'usuario_id', FILTER_VALIDATE_INT);
                        if ($userId) {
                            $this->getGruposDeUsuario($userId);
                        }
                    }
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    $this->createUsuario($data);
                    break;

                case 'PUT':
                    $data = json_decode(file_get_contents('php://input'), true);
                    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de usuario no proporcionado"], 400);
                    }
                    $this->updateUsuario($id, $data);
                    break;

                case 'DELETE':
                    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    if (!$id) {
                        $this->sendResponse(["error" => "ID de usuario no proporcionado"], 400);
                    }
                    $this->deleteUsuario($id);
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
$api = new UsuariosAPI($conn);
$api->handleRequest();
