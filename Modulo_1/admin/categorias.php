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

$busqueda = trim($_GET["busqueda"] ?? "");

if ($busqueda !== "") {

    $texto_busqueda = "%" . $busqueda . "%";

    $consulta = $conexion->prepare(
        "SELECT
            id_categoria,
            nombre,
            descripcion,
            estado,
            fecha_registro
        FROM categorias
        WHERE nombre LIKE ?
           OR descripcion LIKE ?
        ORDER BY id_categoria DESC"
    );

    $consulta->bind_param(
        "ss",
        $texto_busqueda,
        $texto_busqueda
    );

} else {

    $consulta = $conexion->prepare(
        "SELECT
            id_categoria,
            nombre,
            descripcion,
            estado,
            fecha_registro
        FROM categorias
        ORDER BY id_categoria DESC"
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

    <title>Categorías | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/categorias.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-categorias">

            <div>

                <h1>Categorías</h1>

                <p>
                    Administra las categorías de los videojuegos.
                </p>

            </div>

            <a
                href="crear_categoria.php"
                class="boton-nueva-categoria"
            >
                + Nueva categoría
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-categorias">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="panel-categorias">

            <form
                action="categorias.php"
                method="GET"
                class="formulario-busqueda"
            >

                <input
                    type="text"
                    name="busqueda"
                    placeholder="Buscar por nombre o descripción..."
                    value="<?php echo htmlspecialchars($busqueda); ?>"
                >

                <button type="submit">
                    Buscar
                </button>

                <?php if ($busqueda !== "") { ?>

                    <a
                        href="categorias.php"
                        class="boton-limpiar"
                    >
                        Limpiar
                    </a>

                <?php } ?>

            </form>

            <div class="tabla-contenedor">

                <table>

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

                            <?php while ($categoria = $resultado->fetch_assoc()) { ?>

                                <tr>

                                    <td>

                                        <?php
                                        echo $categoria["id_categoria"];
                                        ?>

                                    </td>

                                    <td class="nombre-categoria">

                                        <?php
                                        echo htmlspecialchars(
                                            $categoria["nombre"]
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $categoria["descripcion"] ?? ""
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <span
                                            class="estado-categoria <?php echo $categoria["estado"]; ?>"
                                        >

                                            <?php
                                            echo ucfirst(
                                                $categoria["estado"]
                                            );
                                            ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?php
                                        echo date(
                                            "d/m/Y",
                                            strtotime(
                                                $categoria["fecha_registro"]
                                            )
                                        );
                                        ?>

                                    </td>

                                    <td>

                                        <div class="acciones-categoria">

                                            <a
                                                href="editar_categoria.php?id=<?php echo $categoria["id_categoria"]; ?>"
                                                class="boton-editar"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="cambiar_estado_categoria.php"
                                                method="POST"
                                                onsubmit="return confirmarCambioEstado(
                                                    '<?php echo $categoria["estado"]; ?>',
                                                    '<?php echo htmlspecialchars(
                                                        $categoria["nombre"],
                                                        ENT_QUOTES,
                                                        "UTF-8"
                                                    ); ?>'
                                                );"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id_categoria"
                                                    value="<?php echo $categoria["id_categoria"]; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="boton-estado"
                                                >

                                                    <?php
                                                    echo $categoria["estado"] === "activa"
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
                                    class="sin-resultados"
                                >
                                    No se encontraron categorías.
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

function confirmarCambioEstado(
    estadoActual,
    nombreCategoria
) {

    let mensaje;

    if (estadoActual === "activa") {

        mensaje =
            '¿Estás seguro de desactivar la categoría "' +
            nombreCategoria +
            '"?';

    } else {

        mensaje =
            '¿Estás seguro de activar la categoría "' +
            nombreCategoria +
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