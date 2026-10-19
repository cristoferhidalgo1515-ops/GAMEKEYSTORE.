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

$nombres = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$rol = $_POST["rol"] ?? "";
$estado = $_POST["estado"] ?? "";

$roles_validos = [
    "administrador",
    "cliente",
    "empleado"
];

$estados_validos = [
    "activo",
    "inactivo"
];

if (
    !$id_usuario ||
    $nombres === "" ||
    $apellidos === "" ||
    $correo === ""
) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("Faltan datos obligatorios.")
    );
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header(
        "Location: editar_usuario.php?id=" .
        $id_usuario .
        "&mensaje=" .
        urlencode("El correo no es válido.")
    );
    exit();
}

if (!in_array($rol, $roles_validos, true)) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El rol seleccionado no es válido.")
    );
    exit();
}

if (!in_array($estado, $estados_validos, true)) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );
    exit();
}

/* Evitar quitarse el acceso administrativo */

if ($id_usuario == $_SESSION["id_usuario"]) {
    $rol = "administrador";
    $estado = "activo";
}

/* Revisar que el correo no pertenezca a otra cuenta */

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
        "Location: editar_usuario.php?id=" .
        $id_usuario .
        "&mensaje=" .
        urlencode("Ese correo ya pertenece a otro usuario.")
    );
    exit();
}

$verificar->close();

/* Actualizar */

$actualizar = $conexion->prepare(
    "UPDATE usuarios
     SET
        nombres = ?,
        apellidos = ?,
        correo = ?,
        telefono = ?,
        direccion = ?,
        rol = ?,
        estado = ?
     WHERE id_usuario = ?"
);

$actualizar->bind_param(
    "sssssssi",
    $nombres,
    $apellidos,
    $correo,
    $telefono,
    $direccion,
    $rol,
    $estado,
    $id_usuario
);

if ($actualizar->execute()) {

    if ($id_usuario == $_SESSION["id_usuario"]) {
        $_SESSION["nombres"] = $nombres;
        $_SESSION["apellidos"] = $apellidos;
        $_SESSION["correo"] = $correo;
        $_SESSION["rol"] = "administrador";
    }

    $mensaje = "Usuario actualizado correctamente.";

} else {

    $mensaje = "No se pudo actualizar el usuario.";

}

$actualizar->close();
$conexion->close();

header(
    "Location: usuarios.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>