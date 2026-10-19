<?php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "modulo_3";

$conn = new mysqli(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>