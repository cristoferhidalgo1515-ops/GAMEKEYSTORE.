<?php

session_start();

/* Verificar que exista una sesión iniciada */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* Permitir el acceso solamente al administrador */

if ($_SESSION["rol"] !== "administrador") {
    header(
        "Location: ../login.php?mensaje=" .
        urlencode("Acceso no autorizado.")
    );
    exit();
}

/* Conectar con la base de datos */

include("../config/conexion.php");

/* Obtener el texto escrito en el buscador */

$busqueda = trim($_GET["busqueda"] ?? "");

/* Preparar la consulta */

if ($busqueda !== "") {

    $texto_busqueda = "%" . $busqueda . "%";

    $consulta = $conexion->prepare(
        "SELECT
            id_plataforma,
            nombre,
            descripcion,
            estado,
            fecha_registro
        FROM plataformas
        WHERE nombre LIKE ?
           OR descripcion LIKE ?
        ORDER BY id_plataforma DESC"
    );

    $consulta->bind_param(
        "ss",
        $texto_busqueda,
        $texto_busqueda
    );

} else {

    $consulta = $conexion->prepare(
        "SELECT
            id_plataforma,
            nombre,
            descripcion,
            estado,
            fecha_registro
        FROM plataformas
        ORDER BY id_plataforma DESC"
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

    <title>Plataformas | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/plataformas.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-plataformas">

            <div>

                <h1>Plataformas</h1>

                <p>
                    Administra las plataformas disponibles para los videojuegos.
                </p>

            </div>

            <a
                href="crear_plataforma.php"
                class="boton-nueva-plataforma"
            >
                + Nueva plataforma
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-plataformas">

                <?php
                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>

            </div>

        <?php } ?>

        <section class="panel-plataformas">

            <form
                action="plataformas.php"
                method="GET"
                class="formulario-busqueda-plataforma"
            >

                <input
                    type="text"
                    name="busqueda"
                    placeholder="Buscar por nombre o descripción..."
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
                        href="plataformas.php"
                        class="boton-limpiar-plataforma"
                    >
                        Limpiar
                    </a>

                <?php } ?>

            </form>

            <div class="tabla-plataformas-contenedor">

                <table class="tabla-plataformas">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($resultado->num_rows > 0) { ?>

                            <?php while ($plataforma = $resultado->fetch_assoc()) { ?>

                                <tr>

                                    <td>

                                        <?php
                                        echo $plataforma["id_plataforma"];
                                        ?>

                                    </td>

                                    <td class="nombre-plataforma">

                                        <?php
                                        echo htmlspecialchars(
                                            $plataforma["nombre"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $plataforma["descripcion"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <span
                                            class="estado-plataforma <?php
                                                echo htmlspecialchars(
                                                    $plataforma["estado"],
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                );
                                            ?>"
                                        >

                                            <?php
                                            echo ucfirst(
                                                $plataforma["estado"]
                                            );
                                            ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?php
                                        echo date(
                                            "d/m/Y",
                                            strtotime(
                                                $plataforma["fecha_registro"]
                                            )
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <div class="acciones-plataforma">

                                            <a
                                                href="editar_plataforma.php?id=<?php
                                                    echo $plataforma["id_plataforma"];
                                                ?>"
                                                class="boton-editar-plataforma"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="cambiar_estado_plataforma.php"
                                                method="POST"
                                                onsubmit="return confirmarCambioEstadoPlataforma(
                                                    '<?php
                                                        echo $plataforma["estado"];
                                                    ?>',
                                                    '<?php
                                                        echo htmlspecialchars(
                                                            $plataforma["nombre"],
                                                            ENT_QUOTES,
                                                            "UTF-8"
                                                        );
                                                    ?>'
                                                );"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id_plataforma"
                                                    value="<?php
                                                        echo $plataforma["id_plataforma"];
                                                    ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="boton-estado-plataforma"
                                                >

                                                    <?php
                                                    echo $plataforma["estado"] === "activa"
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
                                    colspan="6"
                                    class="sin-plataformas"
                                >
                                    No se encontraron plataformas.
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

function confirmarCambioEstadoPlataforma(
    estadoActual,
    nombrePlataforma
) {

    let mensaje;

    if (estadoActual === "activa") {

        mensaje =
            '¿Estás seguro de desactivar la plataforma "' +
            nombrePlataforma +
            '"?';

    } else {

        mensaje =
            '¿Estás seguro de activar la plataforma "' +
            nombrePlataforma +
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