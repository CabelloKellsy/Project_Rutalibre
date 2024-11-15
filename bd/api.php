<?php
// ini_set('display_errors', 0); // Desactiva la visualización de errores
// error_reporting(E_ALL); // Reporta todos los errores


header('Content-Type: application/json'); // Establece el tipo de contenido a JSON
include 'connection.php'; // llamar el archivo de conexión

$sql = "SELECT * FROM usuarios"; // tabla usuarios
try {
    $result = $conn->query($sql);

    $usuarios = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $usuarios[] = $row; // Agrega cada fila a un array
    }
    echo json_encode($usuarios); // Devuelve los datos en formato JSON
} catch (PDOException $e) {
    // En caso de error
    echo json_encode(array("error" => "Error en la consulta: " . $e->getMessage()));
}

$conn = null; // Cierra la conexión
?>