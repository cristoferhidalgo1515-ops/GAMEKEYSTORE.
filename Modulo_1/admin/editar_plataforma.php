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

$id_plataforma = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id_plataforma) {

    header(
        "Location: plataformas.php?mensaje=" .
        urlencode("La plataforma seleccionada no es válida.")
    );

    exit();
}

$consulta = $conexion->prepare(
    "SELECT
        id_plataforma,
        nombre,
        descripcion,
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

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar plataforma | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/formulario_plataforma.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-formulario-plataforma">

            <div>

                <h1>Editar plataforma</h1>

                <p>
                    Modifica la información de la plataforma seleccionada.
                </p>

            </div>

            <a
                href="plataformas.php"
                class="boton-volver-plataforma"
            >
                Volver a plataformas
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario-plataforma">

                <?php

                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

        <?php } ?>

        <section class="panel-formulario-plataforma">

            <form
                action="actualizar_plataforma.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="id_plataforma"
                    value="<?php
                        echo (int) $plataforma["id_plataforma"];
                    ?>"
                >

                <div class="campo-plataforma">

                    <label for="nombre">
                        Nombre de la plataforma
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required
                        value="<?php
                            echo htmlspecialchars(
                                $plataforma["nombre"],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                    >

                </div>

                <div class="campo-plataforma">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="255"
                        placeholder="Describe brevemente esta plataforma..."
                    ><?php
                        echo htmlspecialchars(
                            $plataforma["descripcion"] ?? "",
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    ?></textarea>

                </div>

                <div class="campo-plataforma">

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
                            <?php
                            echo $plataforma["estado"] === "activa"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Activa
                        </option>

                        <option
                            value="inactiva"
                            <?php
                            echo $plataforma["estado"] === "inactiva"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Inactiva
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="boton-guardar-plataforma"
                >
                    Guardar cambios
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>

<?php

$conexion->close();

?>