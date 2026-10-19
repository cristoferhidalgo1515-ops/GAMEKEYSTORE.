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

/* Recibir ID */

$id_videojuego = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id_videojuego) {
    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode("El videojuego seleccionado no es válido.")
    );
    exit();
}

/* Buscar videojuego */

$consulta_videojuego = $conexion->prepare(
    "SELECT
        id_videojuego,
        titulo,
        descripcion,
        precio,
        stock,
        imagen,
        id_categoria,
        id_plataforma,
        estado
    FROM videojuegos
    WHERE id_videojuego = ?
    LIMIT 1"
);

$consulta_videojuego->bind_param(
    "i",
    $id_videojuego
);

$consulta_videojuego->execute();

$resultado_videojuego =
    $consulta_videojuego->get_result();

if ($resultado_videojuego->num_rows === 0) {

    $consulta_videojuego->close();
    $conexion->close();

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode("El videojuego no existe.")
    );

    exit();
}

$videojuego =
    $resultado_videojuego->fetch_assoc();

/* Obtener categorías */

$consulta_categorias = $conexion->prepare(
    "SELECT
        id_categoria,
        nombre,
        estado
    FROM categorias
    ORDER BY nombre ASC"
);

$consulta_categorias->execute();

$resultado_categorias =
    $consulta_categorias->get_result();

/* Obtener plataformas */

$consulta_plataformas = $conexion->prepare(
    "SELECT
        id_plataforma,
        nombre,
        estado
    FROM plataformas
    ORDER BY nombre ASC"
);

$consulta_plataformas->execute();

$resultado_plataformas =
    $consulta_plataformas->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar videojuego | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/formulario_videojuego.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-formulario-videojuego">

            <div>

                <h1>Editar videojuego</h1>

                <p>
                    Modifica la información, el stock o la portada.
                </p>

            </div>

            <a
                href="videojuegos.php"
                class="boton-volver-videojuego"
            >
                Volver a videojuegos
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario-videojuego">

                <?php

                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

        <?php } ?>

        <section class="panel-formulario-videojuego">

            <form
                action="actualizar_videojuego.php"
                method="POST"
                enctype="multipart/form-data"
                id="formularioVideojuego"
            >

                <input
                    type="hidden"
                    name="id_videojuego"
                    value="<?php
                        echo (int) $videojuego["id_videojuego"];
                    ?>"
                >

                <input
                    type="hidden"
                    name="imagen_actual"
                    value="<?php
                        echo htmlspecialchars(
                            $videojuego["imagen"] ?? "",
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    ?>"
                >

                <div class="seccion-formulario-videojuego">

                    <h2>Información del videojuego</h2>

                    <div class="campo-videojuego">

                        <label for="titulo">
                            Título del videojuego
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            maxlength="150"
                            required
                            value="<?php
                                echo htmlspecialchars(
                                    $videojuego["titulo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                            ?>"
                        >

                    </div>

                    <div class="campo-videojuego">

                        <label for="descripcion">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            maxlength="1000"
                        ><?php
                            echo htmlspecialchars(
                                $videojuego["descripcion"] ?? "",
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?></textarea>

                        <small>
                            Máximo 1000 caracteres.
                        </small>

                    </div>

                    <div class="fila-formulario-videojuego">

                        <div class="campo-videojuego">

                            <label for="precio">
                                Precio
                            </label>

                            <input
                                type="number"
                                id="precio"
                                name="precio"
                                min="0"
                                step="0.01"
                                required
                                value="<?php
                                    echo htmlspecialchars(
                                        $videojuego["precio"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                ?>"
                            >

                        </div>

                        <div class="campo-videojuego">

                            <label for="stock">
                                Stock
                            </label>

                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                min="0"
                                step="1"
                                required
                                value="<?php
                                    echo (int) $videojuego["stock"];
                                ?>"
                            >

                        </div>

                    </div>

                    <div class="fila-formulario-videojuego">

                        <div class="campo-videojuego">

                            <label for="id_categoria">
                                Categoría
                            </label>

                            <select
                                id="id_categoria"
                                name="id_categoria"
                                required
                            >

                                <?php while (
                                    $categoria =
                                    $resultado_categorias->fetch_assoc()
                                ) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $categoria["id_categoria"];
                                        ?>"
                                        <?php

                                        echo (
                                            (int) $categoria["id_categoria"] ===
                                            (int) $videojuego["id_categoria"]
                                        )
                                            ? "selected"
                                            : "";

                                        ?>
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $categoria["nombre"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

                                        if (
                                            $categoria["estado"] === "inactiva"
                                        ) {
                                            echo " (inactiva)";
                                        }

                                        ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="campo-videojuego">

                            <label for="id_plataforma">
                                Plataforma
                            </label>

                            <select
                                id="id_plataforma"
                                name="id_plataforma"
                                required
                            >

                                <?php while (
                                    $plataforma =
                                    $resultado_plataformas->fetch_assoc()
                                ) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $plataforma["id_plataforma"];
                                        ?>"
                                        <?php

                                        echo (
                                            (int) $plataforma["id_plataforma"] ===
                                            (int) $videojuego["id_plataforma"]
                                        )
                                            ? "selected"
                                            : "";

                                        ?>
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $plataforma["nombre"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

                                        if (
                                            $plataforma["estado"] === "inactiva"
                                        ) {
                                            echo " (inactiva)";
                                        }

                                        ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="campo-videojuego">

                        <label for="estado">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado"
                            required
                        >

                            <option
                                value="activo"
                                <?php
                                    echo $videojuego["estado"] === "activo"
                                        ? "selected"
                                        : "";
                                ?>
                            >
                                Activo
                            </option>

                            <option
                                value="inactivo"
                                <?php
                                    echo $videojuego["estado"] === "inactivo"
                                        ? "selected"
                                        : "";
                                ?>
                            >
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <div class="seccion-imagen-videojuego">

                    <h2>Portada del videojuego</h2>

                    <div class="vista-previa-videojuego">

                        <img
                            src="<?php

                                if (!empty($videojuego["imagen"])) {

                                    echo "../" .
                                        htmlspecialchars(
                                            $videojuego["imagen"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

                                }

                            ?>"
                            alt="Vista previa"
                            id="imagenPreviaVideojuego"
                            class="<?php
                                echo empty($videojuego["imagen"])
                                    ? "imagen-previa-oculta"
                                    : "";
                            ?>"
                        >

                        <div
                            class="marcador-imagen-videojuego"
                            id="marcadorImagenVideojuego"
                            style="<?php
                                echo !empty($videojuego["imagen"])
                                    ? "display: none;"
                                    : "";
                            ?>"
                        >

                            <span>🎮</span>

                            <p>
                                Este videojuego no tiene portada
                            </p>

                        </div>

                    </div>

                    <div class="campo-videojuego">

                        <label for="imagen">
                            Cambiar portada
                        </label>

                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small>
                            Déjalo vacío para conservar la portada actual.
                            Solo JPG, JPEG, PNG o WEBP, máximo 5 MB.
                        </small>

                    </div>

                </div>

                <div class="acciones-formulario-videojuego">

                    <a
                        href="videojuegos.php"
                        class="boton-cancelar-videojuego"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="boton-guardar-videojuego"
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

<script>

const campoImagen =
    document.getElementById("imagen");

const imagenPrevia =
    document.getElementById(
        "imagenPreviaVideojuego"
    );

const marcadorImagen =
    document.getElementById(
        "marcadorImagenVideojuego"
    );

campoImagen.addEventListener(
    "change",
    function () {

        const archivo = this.files[0];

        if (!archivo) {
            return;
        }

        const tiposPermitidos = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!tiposPermitidos.includes(archivo.type)) {

            alert(
                "Selecciona una imagen JPG, JPEG, PNG o WEBP."
            );

            this.value = "";
            return;
        }

        const tamanioMaximo =
            5 * 1024 * 1024;

        if (archivo.size > tamanioMaximo) {

            alert(
                "La imagen no puede superar los 5 MB."
            );

            this.value = "";
            return;
        }

        const lector = new FileReader();

        lector.onload = function (evento) {

            imagenPrevia.src =
                evento.target.result;

            imagenPrevia.classList.remove(
                "imagen-previa-oculta"
            );

            marcadorImagen.style.display = "none";

        };

        lector.readAsDataURL(archivo);

    }
);

</script>

</body>

</html>

<?php

$consulta_videojuego->close();
$consulta_categorias->close();
$consulta_plataformas->close();
$conexion->close();

?>