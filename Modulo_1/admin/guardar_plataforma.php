<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["rol"] !== "administrador") {
    header(
        "Location: ../login.php?mensaje=" .
        urlencode("Acceso no autorizado.")
    );
    exit();
}

include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_plataforma.php");
    exit();
}

$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$estado = trim($_POST["estado"] ?? "");

if ($nombre === "") {

    header(
        "Location: crear_plataforma.php?mensaje=" .
        urlencode("El nombre de la plataforma es obligatorio.")
    );

    exit();
}

if (!in_array($estado, ["activa", "inactiva"], true)) {

    header(
        "Location: crear_plataforma.php?mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );

    exit();
}

/* Verificar si ya existe una plataforma con el mismo nombre */

$verificar = $conexion->prepare(
    "SELECT id_plataforma
     FROM plataformas
     WHERE nombre = ?
     LIMIT 1"
);

$verificar->bind_param(
    "s",
    $nombre
);

$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {

    $verificar->close();
    $conexion->close();

    header(
        "Location: crear_plataforma.php?mensaje=" .
        urlencode("Ya existe una plataforma con ese nombre.")
    );

    exit();
}

$verificar->close();

/* Guardar plataforma */

$guardar = $conexion->prepare(
    "INSERT INTO plataformas (
        nombre,
        descripcion,
        estado
    ) VALUES (?, ?, ?)"
);

$guardar->bind_param(
    "sss",
    $nombre,
    $descripcion,
    $estado
);

if ($guardar->execute()) {

    $mensaje = "Plataforma registrada correctamente.";

    $guardar->close();
    $conexion->close();

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode($mensaje)
    );

    exit();

}

$guardar->close();
$conexion->close();

header(
    "Location: crear_plataforma.php?mensaje=" .
    urlencode("No se pudo guardar la plataforma.")
);

exit();

?>