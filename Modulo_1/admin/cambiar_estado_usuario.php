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

$id_usuario = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id_usuario) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("Usuario no válido.")
    );
    exit();
}

/* Evitar que el administrador desactive su propia cuenta */

if ($id_usuario == $_SESSION["id_usuario"]) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("No puedes cambiar el estado de tu propia cuenta.")
    );
    exit();
}

/* Consultar el estado actual */

$consulta = $conexion->prepare(
    "SELECT estado
     FROM usuarios
     WHERE id_usuario = ?
     LIMIT 1"
);

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {
    $consulta->close();
    $conexion->close();

    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El usuario no existe.")
    );
    exit();
}

$usuario = $resultado->fetch_assoc();
$consulta->close();

/* Cambiar el estado */

$nuevo_estado = $usuario["estado"] === "activo"
    ? "inactivo"
    : "activo";

$actualizar = $conexion->prepare(
    "UPDATE usuarios
     SET estado = ?
     WHERE id_usuario = ?"
);

$actualizar->bind_param(
    "si",
    $nuevo_estado,
    $id_usuario
);

if ($actualizar->execute()) {
    $mensaje = "Estado del usuario actualizado correctamente.";
} else {
    $mensaje = "No se pudo actualizar el estado del usuario.";
}

$actualizar->close();
$conexion->close();

header(
    "Location: usuarios.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>