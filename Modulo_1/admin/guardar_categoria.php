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
    header("Location: crear_categoria.php");
    exit();
}

$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$estado = trim($_POST["estado"] ?? "");

if ($nombre === "") {

    header(
        "Location: crear_categoria.php?mensaje=" .
        urlencode("El nombre de la categoría es obligatorio.")
    );

    exit();
}

if (!in_array($estado, ["activa", "inactiva"])) {

    header(
        "Location: crear_categoria.php?mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );

    exit();
}

/* Comprobar si ya existe */

$verificar = $conexion->prepare(
    "SELECT id_categoria
     FROM categorias
     WHERE nombre = ?
     LIMIT 1"
);

$verificar->bind_param("s", $nombre);
$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {

    $verificar->close();
    $conexion->close();

    header(
        "Location: crear_categoria.php?mensaje=" .
        urlencode("Ya existe una categoría con ese nombre.")
    );

    exit();
}

$verificar->close();

/* Guardar categoría */

$guardar = $conexion->prepare(
    "INSERT INTO categorias (
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

    $mensaje = "Categoría registrada correctamente.";

    $guardar->close();
    $conexion->close();

    header(
        "Location: categorias.php?mensaje=" .
        urlencode($mensaje)
    );

    exit();

}

$guardar->close();
$conexion->close();

header(
    "Location: crear_categoria.php?mensaje=" .
    urlencode("No se pudo guardar la categoría.")
);

exit();

?>