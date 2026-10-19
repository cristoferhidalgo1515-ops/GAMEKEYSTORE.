<?php

session_start();

/* Verificar que exista una sesión iniciada */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* Permitir el acceso solamente al administrador */

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

/* Obtener la búsqueda */

$busqueda = trim($_GET["busqueda"] ?? "");

/* Preparar la consulta */

if ($busqueda !== "") {

    $texto_busqueda = "%" . $busqueda . "%";

    $consulta = $conexion->prepare(
        "SELECT
            videojuegos.id_videojuego,
            videojuegos.titulo,
            videojuegos.descripcion,
            videojuegos.precio,
            videojuegos.stock,
            videojuegos.imagen,
            videojuegos.estado,
            videojuegos.fecha_registro,
            categorias.nombre AS nombre_categoria,
            plataformas.nombre AS nombre_plataforma
        FROM videojuegos
        INNER JOIN categorias
            ON videojuegos.id_categoria = categorias.id_categoria
        INNER JOIN plataformas
            ON videojuegos.id_plataforma = plataformas.id_plataforma
        WHERE videojuegos.titulo LIKE ?
           OR videojuegos.descripcion LIKE ?
           OR categorias.nombre LIKE ?
           OR plataformas.nombre LIKE ?
        ORDER BY videojuegos.id_videojuego DESC"
    );

    $consulta->bind_param(
        "ssss",
        $texto_busqueda,
        $texto_busqueda,
        $texto_busqueda,
        $texto_busqueda
    );

} else {

    $consulta = $conexion->prepare(
        "SELECT
            videojuegos.id_videojuego,
            videojuegos.titulo,
            videojuegos.descripcion,
            videojuegos.precio,
            videojuegos.stock,
            videojuegos.imagen,
            videojuegos.estado,
            videojuegos.fecha_registro,
            categorias.nombre AS nombre_categoria,
            plataformas.nombre AS nombre_plataforma
        FROM videojuegos
        INNER JOIN categorias
            ON videojuegos.id_categoria = categorias.id_categoria
        INNER JOIN plataformas
            ON videojuegos.id_plataforma = plataformas.id_plataforma
        ORDER BY videojuegos.id_videojuego DESC"
    );

}

$consulta->execute();

$resultado = $consulta->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Videojuegos | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/videojuegos.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-videojuegos">

            <div>

                <h1>Videojuegos</h1>

                <p>
                    Administra los videojuegos disponibles en GameKey Store.
                </p>

            </div>

            <a
                href="crear_videojuego.php"
                class="boton-nuevo-videojuego"
            >
                + Nuevo videojuego
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-videojuegos">

                <?php

                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

        <?php } ?>

        <section class="panel-videojuegos">

            <form
                action="videojuegos.php"
                method="GET"
                class="formulario-busqueda-videojuego"
            >

                <input
                    type="text"
                    name="busqueda"
                    placeholder="Buscar por título, categoría o plataforma..."
                    value="<?php
                        echo htmlspecialchars(
                            $busqueda,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    ?>"
                >

                <button type="submit">
                    Buscar
                </button>

                <?php if ($busqueda !== "") { ?>

                    <a
                        href="videojuegos.php"
                        class="boton-limpiar-videojuego"
                    >
                        Limpiar
                    </a>

                <?php } ?>

            </form>

            <div class="tabla-videojuegos-contenedor">

                <table class="tabla-videojuegos">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Videojuego</th>
                            <th>Categoría</th>
                            <th>Plataforma</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($resultado->num_rows > 0) { ?>

                            <?php while ($videojuego = $resultado->fetch_assoc()) { ?>

                                <tr>

                                    <td>

                                        <?php
                                        echo (int) $videojuego["id_videojuego"];
                                        ?>

                                    </td>

                                    <td>

                                        <div class="informacion-videojuego">

                                            <div class="miniatura-videojuego">

                                                <?php if (!empty($videojuego["imagen"])) { ?>

                                                    <img
                                                        src="../<?php
                                                            echo htmlspecialchars(
                                                                $videojuego["imagen"],
                                                                ENT_QUOTES,
                                                                "UTF-8"
                                                            );
                                                        ?>"
                                                        alt="<?php
                                                            echo htmlspecialchars(
                                                                $videojuego["titulo"],
                                                                ENT_QUOTES,
                                                                "UTF-8"
                                                            );
                                                        ?>"
                                                    >

                                                <?php } else { ?>

                                                    <span>🎮</span>

                                                <?php } ?>

                                            </div>

                                            <div class="datos-videojuego">

                                                <strong>

                                                    <?php
                                                    echo htmlspecialchars(
                                                        $videojuego["titulo"],
                                                        ENT_QUOTES,
                                                        "UTF-8"
                                                    );
                                                    ?>

                                                </strong>

                                                <small>

                                                    <?php

                                                    $descripcion =
                                                        $videojuego["descripcion"] ?? "";

                                                    if (strlen($descripcion) > 60) {

                                                        $descripcion =
                                                            substr(
                                                                $descripcion,
                                                                0,
                                                                60
                                                            ) . "...";

                                                    }

                                                    echo htmlspecialchars(
                                                        $descripcion,
                                                        ENT_QUOTES,
                                                        "UTF-8"
                                                    );

                                                    ?>

                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $videojuego["nombre_categoria"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $videojuego["nombre_plataforma"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                    <td class="precio-videojuego">

                                        $<?php
                                        echo number_format(
                                            (float) $videojuego["precio"],
                                            2
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <?php

                                        $stock = (int) $videojuego["stock"];

                                        $clase_stock = "stock-disponible";

                                        if ($stock === 0) {

                                            $clase_stock = "stock-agotado";

                                        } elseif ($stock <= 5) {

                                            $clase_stock = "stock-bajo";

                                        }

                                        ?>

                                        <span class="<?php echo $clase_stock; ?>">

                                            <?php echo $stock; ?>

                                        </span>

                                    </td>

                                    <td>

                                        <span
                                            class="estado-videojuego <?php
                                                echo htmlspecialchars(
                                                    $videojuego["estado"],
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                );
                                            ?>"
                                        >

                                            <?php
                                            echo ucfirst(
                                                $videojuego["estado"]
                                            );
                                            ?>

                                        </span>

                                    </td>

                                    <td>

                                        <div class="acciones-videojuego">

                                            <a
                                                href="editar_videojuego.php?id=<?php
                                                    echo (int) $videojuego["id_videojuego"];
                                                ?>"
                                                class="boton-editar-videojuego"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="cambiar_estado_videojuego.php"
                                                method="POST"
                                                onsubmit="return confirmarCambioEstadoVideojuego(
                                                    '<?php
                                                        echo htmlspecialchars(
                                                            $videojuego["estado"],
                                                            ENT_QUOTES,
                                                            "UTF-8"
                                                        );
                                                    ?>',
                                                    '<?php
                                                        echo htmlspecialchars(
                                                            $videojuego["titulo"],
                                                            ENT_QUOTES,
                                                            "UTF-8"
                                                        );
                                                    ?>'
                                                );"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id_videojuego"
                                                    value="<?php
                                                        echo (int) $videojuego["id_videojuego"];
                                                    ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="boton-estado-videojuego"
                                                >

                                                    <?php
                                                    echo $videojuego["estado"] === "activo"
                                                        ? "Desactivar"
                                                        : "Activar";
                                                    ?>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="sin-videojuegos"
                                >
                                    No se encontraron videojuegos.
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script>

function confirmarCambioEstadoVideojuego(
    estadoActual,
    tituloVideojuego
) {

    let mensaje;

    if (estadoActual === "activo") {

        mensaje =
            '¿Estás seguro de desactivar el videojuego "' +
            tituloVideojuego +
            '"?';

    } else {

        mensaje =
            '¿Estás seguro de activar el videojuego "' +
            tituloVideojuego +
            '"?';

    }

    return confirm(mensaje);
}

</script>

</body>

</html>

<?php

$consulta->close();
$conexion->close();

?>