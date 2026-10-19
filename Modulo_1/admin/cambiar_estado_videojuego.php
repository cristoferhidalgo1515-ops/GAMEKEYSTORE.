<?php

session_start();

/* Verificar sesión */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* Verificar rol */

if (
    !isset($_SESSION["rol"]) ||
    $_SESSION["rol"] !== "administrador"
) {
    header(
        "Location: ../login.php?mensaje=" .
        urlencode("Acceso no autorizado.")
    );
    exit();
}

/* Conexión */

include("../config/conexion.php");

/* Permitir solamente solicitudes POST */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: videojuegos.php");
    exit();
}

/* Recibir ID */

$id_videojuego = filter_input(
    INPUT_POST,
    "id_videojuego",
    FILTER_VALIDATE_INT
);

if (!$id_videojuego) {

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "El videojuego seleccionado no es válido."
        )
    );

    exit();
}

/* Buscar el videojuego */

$consulta = $conexion->prepare(
    "SELECT
        id_videojuego,
        titulo,
        estado
    FROM videojuegos
    WHERE id_videojuego = ?
    LIMIT 1"
);

$consulta->bind_param(
    "i",
    $id_videojuego
);

$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {

    $consulta->close();
    $conexion->close();

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "El videojuego no existe."
        )
    );

    exit();
}

$videojuego = $resultado->fetch_assoc();

$consulta->close();

/* Determinar el nuevo estado */

$nuevo_estado =
    $videojuego["estado"] === "activo"
        ? "inactivo"
        : "activo";

/* Actualizar estado */

$actualizar = $conexion->prepare(
    "UPDATE videojuegos
    SET estado = ?
    WHERE id_videojuego = ?"
);

$actualizar->bind_param(
    "si",
    $nuevo_estado,
    $id_videojuego
);

if ($actualizar->execute()) {

    $titulo = $videojuego["titulo"];

    $mensaje =
        $nuevo_estado === "activo"
            ? 'El videojuego "' .
                $titulo .
                '" fue activado correctamente.'
            : 'El videojuego "' .
                $titulo .
                '" fue desactivado correctamente.';

    $actualizar->close();
    $conexion->close();

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode($mensaje)
    );

    exit();
}

$error = $actualizar->error;

$actualizar->close();
$conexion->close();

header(
    "Location: videojuegos.php?mensaje=" .
    urlencode(
        "No se pudo cambiar el estado del videojuego. Error: " .
        $error
    )
);

exit();

?>