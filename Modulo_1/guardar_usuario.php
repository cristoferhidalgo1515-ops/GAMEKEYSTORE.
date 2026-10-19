<?php

include("config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit();
}

$nombres = trim($_POST["nombres"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmar_contrasena = $_POST["confirmar_contrasena"] ?? "";

if (
    $nombres === "" ||
    $apellidos === "" ||
    $correo === "" ||
    $contrasena === "" ||
    $confirmar_contrasena === ""
) {
    header(
        "Location: registro.php?mensaje=" .
        urlencode("Completa todos los campos obligatorios.")
    );
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header(
        "Location: registro.php?mensaje=" .
        urlencode("El correo electrónico no es válido.")
    );
    exit();
}

if (strlen($contrasena) < 6) {
    header(
        "Location: registro.php?mensaje=" .
        urlencode("La contraseña debe tener al menos 6 caracteres.")
    );
    exit();
}

if ($contrasena !== $confirmar_contrasena) {
    header(
        "Location: registro.php?mensaje=" .
        urlencode("Las contraseñas no coinciden.")
    );
    exit();
}

/* Verificar que el correo no exista */

$consulta_correo = $conexion->prepare(
    "SELECT id_usuario FROM usuarios WHERE correo = ?"
);

$consulta_correo->bind_param("s", $correo);
$consulta_correo->execute();

$resultado_correo = $consulta_correo->get_result();

if ($resultado_correo->num_rows > 0) {
    $consulta_correo->close();

    header(
        "Location: registro.php?mensaje=" .
        urlencode("Ese correo electrónico ya está registrado.")
    );
    exit();
}

$consulta_correo->close();

/* Cifrar la contraseña */

$contrasena_cifrada = password_hash(
    $contrasena,
    PASSWORD_DEFAULT
);

/* Guardar el usuario como cliente */

$rol = "cliente";
$estado = "activo";

$consulta = $conexion->prepare(
    "INSERT INTO usuarios
    (
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

$consulta->bind_param(
    "ssssssss",
    $nombres,
    $apellidos,
    $correo,
    $telefono,
    $contrasena_cifrada,
    $direccion,
    $rol,
    $estado
);

if ($consulta->execute()) {
    $consulta->close();
    $conexion->close();

    header(
        "Location: login.php?mensaje=" .
        urlencode("Cuenta creada correctamente. Ya puedes iniciar sesión.")
    );
    exit();
}

$consulta->close();
$conexion->close();

header(
    "Location: registro.php?mensaje=" .
    urlencode("No se pudo registrar la cuenta.")
);
exit();

?>