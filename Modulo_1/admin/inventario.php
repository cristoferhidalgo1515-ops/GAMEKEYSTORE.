<?php

session_start();

/* ==================================================
   VERIFICAR SESIÓN
================================================== */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* ==================================================
   VERIFICAR ROL
================================================== */

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

/* ==================================================
   CONEXIÓN
================================================== */

include("../config/conexion.php");

/* ==================================================
   RECIBIR FILTROS
================================================== */

$busqueda = trim($_GET["busqueda"] ?? "");
$filtro_stock = trim($_GET["stock"] ?? "todos");

$filtros_permitidos = [
    "todos",
    "disponible",
    "bajo",
    "agotado"
];

if (!in_array($filtro_stock, $filtros_permitidos, true)) {
    $filtro_stock = "todos";
}

/* ==================================================
   CONTADORES DEL INVENTARIO
================================================== */

$consulta_resumen = $conexion->query(
    "SELECT
        COUNT(*) AS total_videojuegos,

        SUM(
            CASE
                WHEN stock > 5 THEN 1
                ELSE 0
            END
        ) AS disponibles,

        SUM(
            CASE
                WHEN stock BETWEEN 1 AND 5 THEN 1
                ELSE 0
            END
        ) AS stock_bajo,

        SUM(
            CASE
                WHEN stock = 0 THEN 1
                ELSE 0
            END
        ) AS agotados,

        SUM(stock) AS unidades_totales

    FROM videojuegos"
);

$resumen = $consulta_resumen->fetch_assoc();

$total_videojuegos =
    (int) ($resumen["total_videojuegos"] ?? 0);

$disponibles =
    (int) ($resumen["disponibles"] ?? 0);

$stock_bajo =
    (int) ($resumen["stock_bajo"] ?? 0);

$agotados =
    (int) ($resumen["agotados"] ?? 0);

$unidades_totales =
    (int) ($resumen["unidades_totales"] ?? 0);

/* ==================================================
   CONSTRUIR CONSULTA DE INVENTARIO
================================================== */

$sql = "
    SELECT
        videojuegos.id_videojuego,
        videojuegos.titulo,
        videojuegos.precio,
        videojuegos.stock,
        videojuegos.imagen,
        videojuegos.estado,
        categorias.nombre AS categoria,
        plataformas.nombre AS plataforma

    FROM videojuegos

    INNER JOIN categorias
        ON videojuegos.id_categoria =
           categorias.id_categoria

    INNER JOIN plataformas
        ON videojuegos.id_plataforma =
           plataformas.id_plataforma

    WHERE 1 = 1
";

$tipos = "";
$parametros = [];

/* ==================================================
   FILTRO DE BÚSQUEDA
================================================== */

if ($busqueda !== "") {

    $sql .= "
        AND (
            videojuegos.titulo LIKE ?
            OR categorias.nombre LIKE ?
            OR plataformas.nombre LIKE ?
        )
    ";

    $texto_busqueda = "%" . $busqueda . "%";

    $tipos .= "sss";

    $parametros[] = $texto_busqueda;
    $parametros[] = $texto_busqueda;
    $parametros[] = $texto_busqueda;
}

/* ==================================================
   FILTRO POR STOCK
================================================== */

if ($filtro_stock === "disponible") {

    $sql .= " AND videojuegos.stock > 5";

} elseif ($filtro_stock === "bajo") {

    $sql .= "
        AND videojuegos.stock BETWEEN 1 AND 5
    ";

} elseif ($filtro_stock === "agotado") {

    $sql .= " AND videojuegos.stock = 0";
}

$sql .= "
    ORDER BY
        videojuegos.stock ASC,
        videojuegos.titulo ASC
";

/* ==================================================
   EJECUTAR CONSULTA
================================================== */

$consulta_inventario = $conexion->prepare($sql);

if ($tipos !== "") {

    $consulta_inventario->bind_param(
        $tipos,
        ...$parametros
    );
}

$consulta_inventario->execute();

$resultado_inventario =
    $consulta_inventario->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Inventario | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/inventario.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-inventario">

            <div>

                <h1>Inventario</h1>

                <p>
                    Controla las existencias de los videojuegos
                    registrados en GameKey Store.
                </p>

            </div>

            <a
                href="crear_videojuego.php"
                class="boton-agregar-inventario"
            >
                + Agregar videojuego
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-inventario">

                <?php

                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

        <?php } ?>

        <section class="tarjetas-inventario">

            <article class="tarjeta-inventario">

                <div class="icono-tarjeta-inventario">
                    🎮
                </div>

                <div>

                    <span>Total de videojuegos</span>

                    <strong>
                        <?php echo $total_videojuegos; ?>
                    </strong>

                </div>

            </article>

            <article class="tarjeta-inventario">

                <div class="icono-tarjeta-inventario">
                    📦
                </div>

                <div>

                    <span>Unidades disponibles</span>

                    <strong>
                        <?php echo $unidades_totales; ?>
                    </strong>

                </div>

            </article>

            <article class="tarjeta-inventario tarjeta-disponible">

                <div class="icono-tarjeta-inventario">
                    ✅
                </div>

                <div>

                    <span>Stock disponible</span>

                    <strong>
                        <?php echo $disponibles; ?>
                    </strong>

                </div>

            </article>

            <article class="tarjeta-inventario tarjeta-bajo">

                <div class="icono-tarjeta-inventario">
                    ⚠️
                </div>

                <div>

                    <span>Stock bajo</span>

                    <strong>
                        <?php echo $stock_bajo; ?>
                    </strong>

                </div>

            </article>

            <article class="tarjeta-inventario tarjeta-agotado">

                <div class="icono-tarjeta-inventario">
                    ❌
                </div>

                <div>

                    <span>Agotados</span>

                    <strong>
                        <?php echo $agotados; ?>
                    </strong>

                </div>

            </article>

        </section>

        <section class="panel-inventario">

            <form
                action="inventario.php"
                method="GET"
                class="formulario-filtros-inventario"
            >

                <div class="campo-busqueda-inventario">

                    <label for="busqueda">
                        Buscar videojuego
                    </label>

                    <input
                        type="text"
                        id="busqueda"
                        name="busqueda"
                        placeholder="Título, categoría o plataforma..."
                        value="<?php
                            echo htmlspecialchars(
                                $busqueda,
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                    >

                </div>

                <div class="campo-filtro-inventario">

                    <label for="stock">
                        Estado del stock
                    </label>

                    <select
                        id="stock"
                        name="stock"
                    >

                        <option
                            value="todos"
                            <?php
                                echo $filtro_stock === "todos"
                                    ? "selected"
                                    : "";
                            ?>
                        >
                            Todos
                        </option>

                        <option
                            value="disponible"
                            <?php
                                echo $filtro_stock === "disponible"
                                    ? "selected"
                                    : "";
                            ?>
                        >
                            Disponible
                        </option>

                        <option
                            value="bajo"
                            <?php
                                echo $filtro_stock === "bajo"
                                    ? "selected"
                                    : "";
                            ?>
                        >
                            Stock bajo
                        </option>

                        <option
                            value="agotado"
                            <?php
                                echo $filtro_stock === "agotado"
                                    ? "selected"
                                    : "";
                            ?>
                        >
                            Agotado
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="boton-filtrar-inventario"
                >
                    Filtrar
                </button>

                <a
                    href="inventario.php"
                    class="boton-limpiar-inventario"
                >
                    Limpiar
                </a>

            </form>

            <div class="contenedor-tabla-inventario">

                <table class="tabla-inventario">

                    <thead>

                        <tr>

                            <th>Portada</th>
                            <th>Videojuego</th>
                            <th>Categoría</th>
                            <th>Plataforma</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Disponibilidad</th>
                            <th>Estado</th>
                            <th>Acción</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (
                        $resultado_inventario->num_rows > 0
                    ) { ?>

                        <?php while (
                            $videojuego =
                            $resultado_inventario->fetch_assoc()
                        ) { ?>

                            <?php

                            $stock_actual =
                                (int) $videojuego["stock"];

                            if ($stock_actual === 0) {

                                $texto_stock = "Agotado";
                                $clase_stock = "estado-agotado";

                            } elseif ($stock_actual <= 5) {

                                $texto_stock = "Stock bajo";
                                $clase_stock = "estado-bajo";

                            } else {

                                $texto_stock = "Disponible";
                                $clase_stock = "estado-disponible";
                            }

                            ?>

                            <tr>

                                <td>

                                    <div class="portada-inventario">

                                        <?php if (
                                            !empty(
                                                $videojuego["imagen"]
                                            )
                                        ) { ?>

                                            <img
                                                src="<?php

                                                    echo "../" .
                                                        htmlspecialchars(
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

                                </td>

                                <td>

                                    <strong class="titulo-inventario">

                                        <?php

                                        echo htmlspecialchars(
                                            $videojuego["titulo"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );

                                        ?>

                                    </strong>

                                </td>

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $videojuego["categoria"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );

                                    ?>

                                </td>

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $videojuego["plataforma"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );

                                    ?>

                                </td>

                                <td>

                                    $<?php

                                    echo number_format(
                                        (float) $videojuego["precio"],
                                        2
                                    );

                                    ?>

                                </td>

                                <td>

                                    <strong class="cantidad-stock">

                                        <?php echo $stock_actual; ?>

                                    </strong>

                                </td>

                                <td>

                                    <span class="<?php
                                        echo $clase_stock;
                                    ?>">

                                        <?php echo $texto_stock; ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="<?php

                                        echo (
                                            $videojuego["estado"] ===
                                            "activo"
                                        )
                                            ? "estado-activo"
                                            : "estado-inactivo";

                                    ?>">

                                        <?php

                                        echo ucfirst(
                                            $videojuego["estado"]
                                        );

                                        ?>

                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="editar_videojuego.php?id=<?php
                                            echo (int)
                                                $videojuego["id_videojuego"];
                                        ?>"
                                        class="boton-editar-stock"
                                    >
                                        Modificar
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="9"
                                class="sin-resultados-inventario"
                            >

                                <span>🎮</span>

                                <strong>
                                    No se encontraron videojuegos
                                </strong>

                                <p>
                                    Prueba cambiando la búsqueda o
                                    el filtro seleccionado.
                                </p>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>

</html>

<?php

$consulta_inventario->close();
$conexion->close();

?>