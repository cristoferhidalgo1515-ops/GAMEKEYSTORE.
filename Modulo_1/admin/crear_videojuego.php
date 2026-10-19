<?php

session_start();

/* Verificar que exista una sesión */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* Permitir solamente administradores */

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

/* Conexión con la base de datos */

include("../config/conexion.php");

/* Obtener categorías activas */

$consulta_categorias = $conexion->prepare(
    "SELECT
        id_categoria,
        nombre
     FROM categorias
     WHERE estado = 'activa'
     ORDER BY nombre ASC"
);

$consulta_categorias->execute();

$resultado_categorias =
    $consulta_categorias->get_result();

/* Obtener plataformas activas */

$consulta_plataformas = $conexion->prepare(
    "SELECT
        id_plataforma,
        nombre
     FROM plataformas
     WHERE estado = 'activa'
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

    <title>Nuevo videojuego | GameKey Store</title>

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

                <h1>Nuevo videojuego</h1>

                <p>
                    Registra un nuevo videojuego en GameKey Store.
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
                action="guardar_videojuego.php"
                method="POST"
                enctype="multipart/form-data"
                id="formularioVideojuego"
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
                            placeholder="Ejemplo: Minecraft"
                            required
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
                            placeholder="Escribe una descripción breve del videojuego..."
                        ></textarea>

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
                                placeholder="0.00"
                                required
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
                                placeholder="0"
                                required
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

                                <option value="">
                                    Selecciona una categoría
                                </option>

                                <?php while (
                                    $categoria =
                                    $resultado_categorias->fetch_assoc()
                                ) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $categoria["id_categoria"];
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $categoria["nombre"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

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

                                <option value="">
                                    Selecciona una plataforma
                                </option>

                                <?php while (
                                    $plataforma =
                                    $resultado_plataformas->fetch_assoc()
                                ) { ?>

                                    <option
                                        value="<?php
                                            echo (int)
                                                $plataforma["id_plataforma"];
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $plataforma["nombre"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

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

                            <option value="activo">
                                Activo
                            </option>

                            <option value="inactivo">
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <div class="seccion-imagen-videojuego">

                    <h2>Portada del videojuego</h2>

                    <div class="vista-previa-videojuego">

                        <img
                            src=""
                            alt="Vista previa"
                            id="imagenPreviaVideojuego"
                            class="imagen-previa-oculta"
                        >

                        <div
                            class="marcador-imagen-videojuego"
                            id="marcadorImagenVideojuego"
                        >

                            <span>🎮</span>

                            <p>
                                Vista previa de la portada
                            </p>

                        </div>

                    </div>

                    <div class="campo-videojuego">

                        <label for="imagen">
                            Seleccionar imagen
                        </label>

                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small>
                            Formatos permitidos: JPG, JPEG, PNG y WEBP.
                            Tamaño máximo: 5 MB.
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
                        Guardar videojuego
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

            imagenPrevia.src = "";

            imagenPrevia.classList.add(
                "imagen-previa-oculta"
            );

            marcadorImagen.style.display = "flex";

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

$consulta_categorias->close();
$consulta_plataformas->close();
$conexion->close();

?>