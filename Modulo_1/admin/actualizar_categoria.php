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
    header("Location: categorias.php");
    exit();
}

$id_categoria = filter_input(
    INPUT_POST,
    "id_categoria",
    FILTER_VALIDATE_INT
);

$nombre = trim($_POST["nombre"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$estado = trim($_POST["estado"] ?? "");

if (!$id_categoria) {
    header(
        "Location: categorias.php?mensaje=" .
        urlencode("La categoría seleccionada no es válida.")
    );
    exit();
}

if ($nombre === "") {
    header(
        "Location: editar_categoria.php?id=" .
        $id_categoria .
        "&mensaje=" .
        urlencode("El nombre es obligatorio.")
    );
    exit();
}

if (!in_array($estado, ["activa", "inactiva"], true)) {
    header(
        "Location: editar_categoria.php?id=" .
        $id_categoria .
        "&mensaje=" .
        urlencode("El estado seleccionado no es válido.")
    );
    exit();
}

/* Verificar que no exista otra categoría con el mismo nombre */

$verificar = $conexion->prepare(
    "SELECT id_categoria
    FROM categorias
    WHERE nombre = ?
      AND id_categoria != ?
    LIMIT 1"
);

$verificar->bind_param(
    "si",
    $nombre,
    $id_categoria
);

$verificar->execute();

$resultado = $verificar->get_result();

if ($resultado->num_rows > 0) {

    $verificar->close();
    $conexion->close();

    header(
        "Location: editar_categoria.php?id=" .
        $id_categoria .
        "&mensaje=" .
        urlencode("Ya existe otra categoría con ese nombre.")
    );

    exit();
}

$verificar->close();

/* Actualizar categoría */

$actualizar = $conexion->prepare(
    "UPDATE categorias
    SET
        nombre = ?,
        descripcion = ?,
        estado = ?
    WHERE id_categoria = ?"
);

$actualizar->bind_param(
    "sssi",
    $nombre,
    $descripcion,
    $estado,
    $id_categoria
);

if ($actualizar->execute()) {

    $mensaje = "Categoría actualizada correctamente.";

} else {

    $mensaje = "No se pudo actualizar la categoría.";

}

$actualizar->close();
$conexion->close();

header(
    "Location: categorias.php?mensaje=" .
    urlencode($mensaje)
);

exit();

?>