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

$contrasena_actual = $_POST["contrasena_actual"] ?? "";
$nueva_contrasena = $_POST["nueva_contrasena"] ?? "";
$confirmar_contrasena = $_POST["confirmar_contrasena"] ?? "";

if (
    $contrasena_actual === "" ||
    $nueva_contrasena === "" ||
    $confirmar_contrasena === ""
) {
    header(
        "Location: perfil.php?mensaje=" .
        urlencode("Completa todos los campos de contraseña.")
    );
    exit();
}

if (strlen($nueva_contrasena) < 8) {
    header(
        "Location: perfil.php?mensaje=" .
        urlencode("La nueva contraseña debe tener al menos 8 caracteres.")
    );
    exit();
}

if ($nueva_contrasena !== $confirmar_contrasena) {
    header(
        "Location: perfil.php?mensaje=" .
        urlencode("Las nuevas contraseñas no coinciden.")
    );
    exit();
}

/* Obtener la contraseña actual guardada */

$consulta = $conexion->prepare(
    "SELECT contrasena
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
        "Location: perfil.php?mensaje=" .
        urlencode("No se encontró la cuenta.")
    );

    exit();
}

$usuario = $resultado->fetch_assoc();

$consulta->close();

/* Verificar la contraseña actual */

if (
    !password_verify(
        $contrasena_actual,
        $usuario["contrasena"]
    )
) {
    $conexion->close();

    header(
        "Location: perfil.php?mensaje=" .
        urlencode("La contraseña actual es incorrecta.")
    );

    exit();
}

/* Evitar usar la misma contraseña */

if (
    password_verify(
        $nueva_contrasena,
        $usuario["contrasena"]
    )
) {
    $conexion->close();

    header(
        "Location: perfil.php?mensaje=" .
        urlencode("La nueva contraseña debe ser diferente a la actual.")
    );

    exit();
}

/* Crear el nuevo hash */

$nuevo_hash = password_hash(
    $nueva_contrasena,
    PASSWORD_DEFAULT
);

/* Actualizar la contraseña */

$actualizar = $conexion->prepare(
    "UPDATE usuarios
     SET contrasena = ?
     WHERE id_usuario = ?"
);

$actualizar->bind_param(
    "si",
    $nuevo_hash,
    $id_usuario
);

if ($actualizar->execute()) {

    $mensaje = "Contraseña actualizada correctamente.";

} else {

    $mensaje = "No se pudo actualizar la contraseña.";

}

$actualizar->close();
$conexion->close();

header(
    "Location: perfil.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>