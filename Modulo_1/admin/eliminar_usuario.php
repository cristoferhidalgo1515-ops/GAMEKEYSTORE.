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
    header("Location: usuarios.php");
    exit();
}

$id_usuario = filter_input(
    INPUT_POST,
    "id_usuario",
    FILTER_VALIDATE_INT
);

if (!$id_usuario) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El usuario seleccionado no es válido.")
    );
    exit();
}

/* Evitar eliminar la cuenta actual */

if ($id_usuario == $_SESSION["id_usuario"]) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("No puedes eliminar tu propia cuenta.")
    );
    exit();
}

/* Verificar que el usuario exista */

$verificar = $conexion->prepare(
    "SELECT id_usuario
     FROM usuarios
     WHERE id_usuario = ?
     LIMIT 1"
);

$verificar->bind_param("i", $id_usuario);
$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows !== 1) {
    $verificar->close();
    $conexion->close();

    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El usuario no existe.")
    );
    exit();
}

$verificar->close();

/* Eliminar usuario */

$eliminar = $conexion->prepare(
    "DELETE FROM usuarios
     WHERE id_usuario = ?"
);

$eliminar->bind_param("i", $id_usuario);

if ($eliminar->execute()) {
    $mensaje = "Usuario eliminado correctamente.";
} else {
    $mensaje = "No se pudo eliminar el usuario.";
}

$eliminar->close();
$conexion->close();

header(
    "Location: usuarios.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>