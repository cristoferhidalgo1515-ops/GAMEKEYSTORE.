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

$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$estado = trim($_POST["estado"] ?? "");

if (!$id_plataforma) {

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("La plataforma seleccionada no es válida.")
    );

    exit();
}

if ($nombre === "") {

    header(
        "Location: editar_plataforma.php?id=" .
        $id_plataforma .
        "&mensaje=" .
        urlencode("El nombre de la plataforma es obligatorio.")
    );

    exit();
}

if (strlen($nombre) > 100) {

    header(
        "Location: editar_plataforma.php?id=" .
        $id_plataforma .
        "&mensaje=" .
        urlencode("El nombre no puede superar los 100 caracteres.")
    );

    exit();
}

if (strlen($descripcion) > 255) {

    header(
        "Location: editar_plataforma.php?id=" .
        $id_plataforma .
        "&mensaje=" .
        urlencode("La descripción no puede superar los 255 caracteres.")
    );

    exit();
}

if (!in_array($estado, ["activa", "inactiva"], true)) {

    header(
        "Location: editar_plataforma.php?id=" .
        $id_plataforma .
        "&mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );

    exit();
}

/* Verificar que la plataforma exista */

$verificar_existencia = $conexion->prepare(
    "SELECT id_plataforma
     FROM plataformas
     WHERE id_plataforma = ?
     LIMIT 1"
);

$verificar_existencia->bind_param(
    "i",
    $id_plataforma
);

$verificar_existencia->execute();

$resultado_existencia =
    $verificar_existencia->get_result();

if ($resultado_existencia->num_rows === 0) {

    $verificar_existencia->close();
    $conexion->close();

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("La plataforma no existe.")
    );

    exit();
}

$verificar_existencia->close();

/* Evitar nombres duplicados */

$verificar_nombre = $conexion->prepare(
    "SELECT id_plataforma
     FROM plataformas
     WHERE nombre = ?
       AND id_plataforma != ?
     LIMIT 1"
);

$verificar_nombre->bind_param(
    "si",
    $nombre,
    $id_plataforma
);

$verificar_nombre->execute();

$resultado_nombre =
    $verificar_nombre->get_result();

if ($resultado_nombre->num_rows > 0) {

    $verificar_nombre->close();
    $conexion->close();

    header(
        "Location: editar_plataforma.php?id=" .
        $id_plataforma .
        "&mensaje=" .
        urlencode("Ya existe otra plataforma con ese nombre.")
    );

    exit();
}

$verificar_nombre->close();

/* Actualizar la plataforma */

$actualizar = $conexion->prepare(
    "UPDATE plataformas
     SET
        nombre = ?,
        descripcion = ?,
        estado = ?
     WHERE id_plataforma = ?"
);

$actualizar->bind_param(
    "sssi",
    $nombre,
    $descripcion,
    $estado,
    $id_plataforma
);

if ($actualizar->execute()) {

    $actualizar->close();
    $conexion->close();

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("Plataforma actualizada correctamente.")
    );

    exit();
}

$actualizar->close();
$conexion->close();

header(
    "Location: editar_plataforma.php?id=" .
    $id_plataforma .
    "&mensaje=" .
    urlencode("No se pudo actualizar la plataforma.")
);

exit();

?>