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
    header("Location: categorias.php");
    exit();
}

$id_categoria = filter_input(
    INPUT_POST,
    "id_categoria",
    FILTER_VALIDATE_INT
);

if (!$id_categoria) {
    header(
        "Location: categorias.php?mensaje=" .
        urlencode("La categoría seleccionada no es válida.")
    );
    exit();
}

/* Buscar la categoría y su estado actual */

$consulta = $conexion->prepare(
    "SELECT nombre, estado
     FROM categorias
     WHERE id_categoria = ?
     LIMIT 1"
);

$consulta->bind_param(
    "i",
    $id_categoria
);

$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {

    $consulta->close();
    $conexion->close();

    header(
        "Location: categorias.php?mensaje=" .
        urlencode("La categoría no fue encontrada.")
    );

    exit();
}

$categoria = $resultado->fetch_assoc();

$consulta->close();

/* Determinar el nuevo estado */

if ($categoria["estado"] === "activa") {
    $nuevo_estado = "inactiva";
} else {
    $nuevo_estado = "activa";
}

/* Actualizar el estado */

$actualizar = $conexion->prepare(
    "UPDATE categorias
     SET estado = ?
     WHERE id_categoria = ?"
);

$actualizar->bind_param(
    "si",
    $nuevo_estado,
    $id_categoria
);

if ($actualizar->execute()) {

    if ($nuevo_estado === "activa") {
        $mensaje = "Categoría activada correctamente.";
    } else {
        $mensaje = "Categoría desactivada correctamente.";
    }

} else {

    $mensaje = "No se pudo cambiar el estado de la categoría.";

}

$actualizar->close();
$conexion->close();

header(
    "Location: categorias.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>