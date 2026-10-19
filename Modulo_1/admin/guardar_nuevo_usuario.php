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
    header("Location: crear_usuario.php");
    exit();
}

$nombres = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$rol = $_POST["rol"] ?? "";
$estado = $_POST["estado"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";
$confirmar_contrasena = $_POST["confirmar_contrasena"] ?? "";

$roles_validos = [
    "administrador",
    "empleado",
    "cliente"
];

$estados_validos = [
    "activo",
    "inactivo"
];

if (
    $nombres === "" ||
    $apellidos === "" ||
    $correo === "" ||
    $rol === "" ||
    $estado === "" ||
    $contrasena === "" ||
    $confirmar_contrasena === ""
) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("Completa todos los campos obligatorios.")
    );
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("El correo electrónico no es válido.")
    );
    exit();
}

if (!in_array($rol, $roles_validos, true)) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("El rol seleccionado no es válido.")
    );
    exit();
}

if (!in_array($estado, $estados_validos, true)) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );
    exit();
}

if (strlen($contrasena) < 8) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("La contraseña debe tener al menos 8 caracteres.")
    );
    exit();
}

if ($contrasena !== $confirmar_contrasena) {
    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("Las contraseñas no coinciden.")
    );
    exit();
}

/* Verificar correo repetido */

$verificar = $conexion->prepare(
    "SELECT id_usuario
     FROM usuarios
     WHERE correo = ?
     LIMIT 1"
);

$verificar->bind_param("s", $correo);
$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {
    $verificar->close();
    $conexion->close();

    header(
        "Location: crear_usuario.php?mensaje=" .
        urlencode("Ya existe una cuenta con ese correo.")
    );
    exit();
}

$verificar->close();

/* Encriptar contraseña */

$contrasena_hash = password_hash(
    $contrasena,
    PASSWORD_DEFAULT
);

/* Insertar usuario */

$guardar = $conexion->prepare(
    "INSERT INTO usuarios (
        nombres,
        apellidos,
        correo,
        telefono,
        contrasena,
        direccion,
        rol,
        estado
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

$guardar->bind_param(
    "ssssssss",
    $nombres,
    $apellidos,
    $correo,
    $telefono,
    $contrasena_hash,
    $direccion,
    $rol,
    $estado
);

if ($guardar->execute()) {
    $mensaje = "Usuario creado correctamente.";
} else {
    $mensaje = "No se pudo crear el usuario.";
}

$guardar->close();
$conexion->close();

header(
    "Location: usuarios.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>