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

$id_categoria = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id_categoria) {
    header(
        "Location: categorias.php?mensaje=" .
        urlencode("La categoría seleccionada no es válida.")
    );
    exit();
}

$consulta = $conexion->prepare(
    "SELECT
        id_categoria,
        nombre,
        descripcion,
        estado
    FROM categorias
    WHERE id_categoria = ?
    LIMIT 1"
);

$consulta->bind_param("i", $id_categoria);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {

    $consulta->close();
    $conexion->close();

    header(
        "Location: categorias.php?mensaje=" .
        urlencode("La categoría no fue encontrada.")
    );

    exit();
}

$categoria = $resultado->fetch_assoc();

$consulta->close();
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar categoría | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/formulario_categoria.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-formulario">

            <div>

                <h1>Editar categoría</h1>

                <p>
                    Modifica la información de la categoría seleccionada.
                </p>

            </div>

            <a href="categorias.php" class="boton-volver">
                Volver a categorías
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="panel-formulario-categoria">

            <form
                action="actualizar_categoria.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="id_categoria"
                    value="<?php echo $categoria["id_categoria"]; ?>"
                >

                <div class="campo-formulario">

                    <label for="nombre">
                        Nombre de la categoría
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        value="<?php echo htmlspecialchars($categoria["nombre"]); ?>"
                        required
                    >

                </div>

                <div class="campo-formulario">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="255"
                    ><?php echo htmlspecialchars($categoria["descripcion"] ?? ""); ?></textarea>

                </div>

                <div class="campo-formulario">

                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        required
                    >

                        <option
                            value="activa"
                            <?php echo $categoria["estado"] === "activa" ? "selected" : ""; ?>
                        >
                            Activa
                        </option>

                        <option
                            value="inactiva"
                            <?php echo $categoria["estado"] === "inactiva" ? "selected" : ""; ?>
                        >
                            Inactiva
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="boton-guardar-categoria"
                >
                    Guardar cambios
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>