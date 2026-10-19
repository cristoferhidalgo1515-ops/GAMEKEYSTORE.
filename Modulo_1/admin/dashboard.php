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
   FUNCIÓN PARA CONTAR REGISTROS
================================================== */

function obtenerTotal(
    mysqli $conexion,
    string $consulta
): int {

    $resultado = $conexion->query($consulta);

    if (!$resultado) {
        return 0;
    }

    $fila = $resultado->fetch_assoc();

    return (int) ($fila["total"] ?? 0);
}

/* ==================================================
   CONTADORES DE USUARIOS
================================================== */

$total_usuarios = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM usuarios"
);

$total_usuarios_activos = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM usuarios
     WHERE estado = 'activo'"
);

$total_administradores = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM usuarios
     WHERE rol = 'administrador'"
);

/* ==================================================
   CONTADORES DE VIDEOJUEGOS
================================================== */

$total_videojuegos = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM videojuegos"
);

$total_videojuegos_activos = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM videojuegos
     WHERE estado = 'activo'"
);

$total_stock_bajo = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM videojuegos
     WHERE stock BETWEEN 1 AND 5"
);

$total_agotados = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM videojuegos
     WHERE stock = 0"
);

/* ==================================================
   CONTADORES DE CATEGORÍAS Y PLATAFORMAS
================================================== */

$total_categorias = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM categorias"
);

$total_plataformas = obtenerTotal(
    $conexion,
    "SELECT COUNT(*) AS total
     FROM plataformas"
);

/* ==================================================
   TOTAL DE UNIDADES EN INVENTARIO
================================================== */

$total_unidades = 0;

$consulta_unidades = $conexion->query(
    "SELECT COALESCE(SUM(stock), 0) AS total
     FROM videojuegos"
);

if ($consulta_unidades) {

    $fila_unidades =
        $consulta_unidades->fetch_assoc();

    $total_unidades =
        (int) ($fila_unidades["total"] ?? 0);
}

/* ==================================================
   VIDEOJUEGOS CON STOCK BAJO O AGOTADO
================================================== */

$consulta_alertas = $conexion->query(
    "SELECT
        id_videojuego,
        titulo,
        stock,
        imagen,
        estado
     FROM videojuegos
     WHERE stock <= 5
     ORDER BY stock ASC, titulo ASC
     LIMIT 6"
);

/* ==================================================
   NOMBRE DEL ADMINISTRADOR
================================================== */

$nombre_administrador =
    $_SESSION["nombres"] ??
    $_SESSION["nombre"] ??
    "Administrador";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Administrador | GameKey Store
    </title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado">

            <div>

                <h1>Panel administrativo</h1>

                <p>

                    Bienvenido,

                    <?php

                    echo htmlspecialchars(
                        $nombre_administrador,
                        ENT_QUOTES,
                        "UTF-8"
                    );

                    ?>

                </p>

            </div>

            <div class="usuario">
                Administrador
            </div>

        </header>

        <!-- ==========================================
             TARJETAS PRINCIPALES
        =========================================== -->

        <section class="tarjetas">

            <article class="tarjeta">

                <span>Total de usuarios</span>

                <strong>
                    <?php echo $total_usuarios; ?>
                </strong>

                <p>
                    <?php echo $total_usuarios_activos; ?>
                    cuentas activas
                </p>

            </article>

            <article class="tarjeta">

                <span>Videojuegos</span>

                <strong>
                    <?php echo $total_videojuegos; ?>
                </strong>

                <p>
                    <?php echo $total_videojuegos_activos; ?>
                    videojuegos activos
                </p>

            </article>

            <article class="tarjeta">

                <span>Categorías</span>

                <strong>
                    <?php echo $total_categorias; ?>
                </strong>

                <p>
                    Categorías registradas
                </p>

            </article>

            <article class="tarjeta">

                <span>Plataformas</span>

                <strong>
                    <?php echo $total_plataformas; ?>
                </strong>

                <p>
                    Plataformas registradas
                </p>

            </article>

            <article class="tarjeta">

                <span>Stock bajo</span>

                <strong>
                    <?php echo $total_stock_bajo; ?>
                </strong>

                <p>
                    Juegos con 1 a 5 unidades
                </p>

            </article>

            <article class="tarjeta">

                <span>Agotados</span>

                <strong>
                    <?php echo $total_agotados; ?>
                </strong>

                <p>
                    Juegos sin existencias
                </p>

            </article>

        </section>

        <!-- ==========================================
             RESUMEN DEL INVENTARIO
        =========================================== -->

        <section class="panel-bienvenida">

            <div class="titulo-seccion-dashboard">

                <div>

                    <h2>Resumen del inventario</h2>

                    <p>
                        Estado actual de los videojuegos
                        registrados en el sistema.
                    </p>

                </div>

                <a
                    href="inventario.php"
                    class="boton-panel"
                >
                    Ver inventario
                </a>

            </div>

            <div class="modulos">

                <div class="modulo">

                    <h3>
                        <?php echo $total_unidades; ?>
                    </h3>

                    <p>
                        Unidades disponibles entre todos
                        los videojuegos.
                    </p>

                    <a href="inventario.php">
                        Revisar existencias
                    </a>

                </div>

                <div class="modulo">

                    <h3>
                        <?php echo $total_stock_bajo; ?>
                    </h3>

                    <p>
                        Videojuegos que necesitan
                        reposición de stock.
                    </p>

                    <a href="inventario.php?stock=bajo">
                        Ver stock bajo
                    </a>

                </div>

                <div class="modulo">

                    <h3>
                        <?php echo $total_agotados; ?>
                    </h3>

                    <p>
                        Videojuegos que actualmente
                        están agotados.
                    </p>

                    <a href="inventario.php?stock=agotado">
                        Ver agotados
                    </a>

                </div>

            </div>

        </section>

        <!-- ==========================================
             ALERTAS DE STOCK
        =========================================== -->

        <section class="panel-bienvenida">

            <div class="titulo-seccion-dashboard">

                <div>

                    <h2>Alertas de stock</h2>

                    <p>
                        Videojuegos con cinco unidades
                        o menos.
                    </p>

                </div>

            </div>

            <?php if (
                $consulta_alertas &&
                $consulta_alertas->num_rows > 0
            ) { ?>

                <div class="lista-alertas-dashboard">

                    <?php while (
                        $videojuego =
                        $consulta_alertas->fetch_assoc()
                    ) { ?>

                        <?php

                        $stock =
                            (int) $videojuego["stock"];

                        $texto_stock =
                            $stock === 0
                                ? "Agotado"
                                : "Stock bajo";

                        $clase_stock =
                            $stock === 0
                                ? "alerta-agotado"
                                : "alerta-bajo";

                        ?>

                        <article class="alerta-dashboard">

                            <div class="imagen-alerta-dashboard">

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

                            <div class="informacion-alerta-dashboard">

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $videojuego["titulo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );

                                    ?>

                                </strong>

                                <p>
                                    Existencias:
                                    <?php echo $stock; ?>
                                </p>

                            </div>

                            <span class="<?php
                                echo $clase_stock;
                            ?>">

                                <?php echo $texto_stock; ?>

                            </span>

                            <a
                                href="editar_videojuego.php?id=<?php
                                    echo (int)
                                        $videojuego["id_videojuego"];
                                ?>"
                                class="boton-modificar-alerta"
                            >
                                Modificar
                            </a>

                        </article>

                    <?php } ?>

                </div>

            <?php } else { ?>

                <div class="sin-alertas-dashboard">

                    <span>✅</span>

                    <strong>
                        No hay alertas de stock
                    </strong>

                    <p>
                        Todos los videojuegos tienen más
                        de cinco unidades disponibles.
                    </p>

                </div>

            <?php } ?>

        </section>

        <!-- ==========================================
             MÓDULOS DEL SISTEMA
        =========================================== -->

        <section class="panel-bienvenida">

            <h2>GAMEKEY STORE</h2>

            <p>
                Desde este panel puedes administrar
                la información principal del sistema.
            </p>

            <div class="modulos">

                <div class="modulo">

                    <h3>Usuarios</h3>

                    <p>
                        Gestionar clientes, empleados
                        y administradores.
                    </p>

                    <a href="usuarios.php">
                        Administrar
                    </a>

                </div>

                <div class="modulo">

                    <h3>Videojuegos</h3>

                    <p>
                        Crear, editar y administrar
                        los videojuegos de la tienda.
                    </p>

                    <a href="videojuegos.php">
                        Administrar
                    </a>

                </div>

                <div class="modulo">

                    <h3>Categorías</h3>

                    <p>
                        Organizar los videojuegos
                        por tipo o género.
                    </p>

                    <a href="categorias.php">
                        Administrar
                    </a>

                </div>

                <div class="modulo">

                    <h3>Plataformas</h3>

                    <p>
                        Administrar PlayStation,
                        Xbox, Nintendo y PC.
                    </p>

                    <a href="plataformas.php">
                        Administrar
                    </a>

                </div>

                <div class="modulo">

                    <h3>Catálogo público</h3>

                    <p>
                        Módulo desarrollado por
                        el integrante 2.
                    </p>

                    <a
                        href="#"
                        class="desactivado"
                    >
                        Pendiente
                    </a>

                </div>

                <div class="modulo">

                    <h3>Pedidos y ventas</h3>

                    <p>
                        Módulo desarrollado por
                        el integrante 3.
                    </p>

                    <a
                        href="#"
                        class="desactivado"
                    >
                        Pendiente
                    </a>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>

<?php

$conexion->close();

?>