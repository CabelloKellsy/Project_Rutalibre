<?php

// variables para la conecion a base de datos
$host = "localhost";
$port = "3306";
$database = "bd_rutalibre3";

//usuario y contraseña
$user = "root";
$password = "";

//creamos una cadena de conexion
$link = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

//creamos un objeto PDO
try {
    $conn = new PDO($link, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    // echo "Conexión exitosa";
    // echo "Conexion a la base de datos exitosa";
} catch (PDOException $e) {
    // echo "Error en la conexion: " . $e->getMessage();
    // error_log($e->getMessage());

    echo json_encode(array("error" => "Error en la conexión: " . $e->getMessage()));
    exit; // Termina el script
}
