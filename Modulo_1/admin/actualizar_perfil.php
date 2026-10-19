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
    header("Location: perfil.php");
    exit();
}

$id_usuario = $_SESSION["id_usuario"];

$nombres = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");

if (
    $nombres === "" ||
    $apellidos === "" ||
    $correo === ""
) {
    header(
        "Location: perfil.php?mensaje=" .
        urlencode("Completa los campos obligatorios.")
    );
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header(
        "Location: perfil.php?mensaje=" .
        urlencode("El correo electrónico no es válido.")
    );
    exit();
}

/* Verificar que el correo no pertenezca a otro usuario */

$verificar = $conexion->prepare(
    "SELECT id_usuario
     FROM usuarios
     WHERE correo = ?
       AND id_usuario != ?
     LIMIT 1"
);

$verificar->bind_param(
    "si",
    $correo,
    $id_usuario
);

$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {

    $verificar->close();
    $conexion->close();

    header(
        "Location: perfil.php?mensaje=" .
        urlencode("Ese correo ya está registrado en otra cuenta.")
    );

    exit();
}

$verificar->close();

/* Actualizar los datos */

$actualizar = $conexion->prepare(
    "UPDATE usuarios
     SET
        nombres = ?,
        apellidos = ?,
        correo = ?,
        telefono = ?,
        direccion = ?
     WHERE id_usuario = ?"
);

$actualizar->bind_param(
    "sssssi",
    $nombres,
    $apellidos,
    $correo,
    $telefono,
    $direccion,
    $id_usuario
);

if ($actualizar->execute()) {

    $_SESSION["nombres"] = $nombres;
    $_SESSION["apellidos"] = $apellidos;
    $_SESSION["correo"] = $correo;

    $mensaje = "Perfil actualizado correctamente.";

} else {

    $mensaje = "No se pudo actualizar el perfil.";

}

$actualizar->close();
$conexion->close();

header(
    "Location: perfil.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>