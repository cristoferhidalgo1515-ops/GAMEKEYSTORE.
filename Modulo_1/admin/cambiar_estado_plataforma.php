<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

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

include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: plataformas.php");
    exit();
}

$id_plataforma = filter_input(
    INPUT_POST,
    "id_plataforma",
    FILTER_VALIDATE_INT
);

if (!$id_plataforma) {

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("La plataforma seleccionada no es válida.")
    );

    exit();
}

/* Buscar la plataforma y conocer su estado actual */

$consulta = $conexion->prepare(
    "SELECT
        nombre,
        estado
     FROM plataformas
     WHERE id_plataforma = ?
     LIMIT 1"
);

$consulta->bind_param(
    "i",
    $id_plataforma
);

$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {

    $consulta->close();
    $conexion->close();

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("La plataforma no existe.")
    );

    exit();
}

$plataforma = $resultado->fetch_assoc();

$consulta->close();

/* Cambiar el estado */

$nuevo_estado =
    $plataforma["estado"] === "activa"
        ? "inactiva"
        : "activa";

$actualizar = $conexion->prepare(
    "UPDATE plataformas
     SET estado = ?
     WHERE id_plataforma = ?"
);

$actualizar->bind_param(
    "si",
    $nuevo_estado,
    $id_plataforma
);

if ($actualizar->execute()) {

    $nombre_plataforma = $plataforma["nombre"];

    $mensaje =
        $nuevo_estado === "activa"
            ? 'La plataforma "' .
                $nombre_plataforma .
                '" fue activada correctamente.'
            : 'La plataforma "' .
                $nombre_plataforma .
                '" fue desactivada correctamente.';

    $actualizar->close();
    $conexion->close();

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode($mensaje)
    );

    exit();
}

$actualizar->close();
$conexion->close();

header(
    "Location: plataformas.php?mensaje=" .
    urlencode("No se pudo cambiar el estado de la plataforma.")
);

exit();

?>