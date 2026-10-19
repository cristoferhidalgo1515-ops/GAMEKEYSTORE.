<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'gamekey_store') { header('Location: ../../index.php'); exit; }


// Obtener el ID enviado desde el catálogo
$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;


// Datos temporales de los videojuegos.
// Más adelante estos datos vendrán desde MySQL.
$juegos = [

    1 => [
        "nombre" => "Marvel's Spider-Man 2",
        "precio" => 49.99,
        "plataforma" => "PlayStation 5",
        "categoria" => "Acción",
        "imagen" => "spiderman2.jpg",
        "tipo" => "Digital",
        "stock" => 10,
        "descripcion" => "Explora una gran ciudad mientras Peter Parker y Miles Morales enfrentan nuevos enemigos y amenazas.",
        "desarrollador" => "Insomniac Games"
    ],

    2 => [
        "nombre" => "Elden Ring",
        "precio" => 39.99,
        "plataforma" => "PC",
        "categoria" => "RPG",
        "imagen" => "eldenring.jpg",
        "tipo" => "Digital",
        "stock" => 8,
        "descripcion" => "Recorre un enorme mundo abierto lleno de enemigos, secretos, fortalezas y desafíos.",
        "desarrollador" => "FromSoftware"
    ],

    3 => [
        "nombre" => "God of War Ragnarök",
        "precio" => 49.99,
        "plataforma" => "PlayStation 5",
        "categoria" => "Aventura",
        "imagen" => "gow.jpg",
        "tipo" => "Digital",
        "stock" => 6,
        "descripcion" => "Acompaña a Kratos y Atreus en una aventura mitológica llena de acción y grandes desafíos.",
        "desarrollador" => "Santa Monica Studio"
    ],

    4 => [
        "nombre" => "Forza Horizon 5",
        "precio" => 44.99,
        "plataforma" => "Xbox",
        "categoria" => "Carreras",
        "imagen" => "forza5.jpg",
        "tipo" => "Digital",
        "stock" => 12,
        "descripcion" => "Explora un enorme mundo abierto y participa en diferentes competencias automovilísticas.",
        "desarrollador" => "Playground Games"
    ],

    5 => [
        "nombre" => "EA Sports FC 26",
        "precio" => 59.99,
        "plataforma" => "PlayStation 5",
        "categoria" => "Deportes",
        "imagen" => "fc26.jpg",
        "tipo" => "Digital",
        "stock" => 15,
        "descripcion" => "Disfruta partidos de fútbol, diferentes equipos, competiciones y modos de juego.",
        "desarrollador" => "EA Sports"
    ],

    6 => [
        "nombre" => "Resident Evil 4",
        "precio" => 34.99,
        "plataforma" => "PC",
        "categoria" => "Terror",
        "imagen" => "re4.jpg",
        "tipo" => "Digital",
        "stock" => 7,
        "descripcion" => "Sobrevive a una peligrosa misión llena de misterio, enemigos y momentos de tensión.",
        "desarrollador" => "Capcom"
    ]

];


// Si el ID no existe, usamos el juego 1
if (!isset($juegos[$id])) {
    $id = 1;
}


// Guardar juego seleccionado
$juego = $juegos[$id];

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($juego["nombre"]); ?>
        | GAMEKEY STORE
    </title>

    <link
        rel="stylesheet"
        href="../css/estilos.css">

    <link
        rel="stylesheet"
        href="../css/detalle.css">

</head>

<body>


<!-- ============================
     ENCABEZADO
============================ -->

<header class="encabezado">

    <a href="index.php" class="logo">

        <span class="logo-icono">
            🎮
        </span>

        <div>

            <h1>GAMEKEY</h1>

            <p>STORE</p>

        </div>

    </a>


    <button
        type="button"
        class="boton-menu"
        id="botonMenu"
        aria-label="Abrir menú">

        ☰

    </button>


    <nav
        class="menu"
        id="menuPrincipal">

        <a href="index.php">
            Inicio
        </a>

        <a
            href="catalogo.php"
            class="activo">

            Juegos

        </a>

        <a href="categorias.php">
            Categorías
        </a>

<a href="soporte.php">
    Soporte
</a>
    </nav>


    <div class="acciones">

        <a
            href="#"
            class="cuenta">

            👤 Mi cuenta

        </a>


        <a
            href="carrito.php"
            class="carrito">

            🛒 Carrito

            <span id="cantidadCarrito">
                0
            </span>

        </a>

    </div>

</header>



<!-- ============================
     CONTENIDO PRINCIPAL
============================ -->

<main class="pagina-detalle">


    <!-- VOLVER -->

    <div class="zona-volver">

        <a
            href="catalogo.php"
            class="volver-catalogo">

            ← Volver al catálogo

        </a>

    </div>



    <!-- DETALLE -->

    <section class="detalle-producto">


        <!-- PORTADA -->

        <div class="detalle-portada">

            <img
                src="../img/juegos/<?php echo htmlspecialchars($juego["imagen"]); ?>"
                alt="<?php echo htmlspecialchars($juego["nombre"]); ?>">

        </div>



        <!-- INFORMACIÓN -->

        <div class="detalle-informacion">


            <div class="etiquetas-detalle">

                <span>
                    <?php echo htmlspecialchars($juego["plataforma"]); ?>
                </span>

                <span>
                    <?php echo htmlspecialchars($juego["categoria"]); ?>
                </span>

                <span>
                    <?php echo htmlspecialchars($juego["tipo"]); ?>
                </span>

            </div>


            <h1>

                <?php echo htmlspecialchars($juego["nombre"]); ?>

            </h1>


            <div class="estrellas">

                ★★★★★

                <span>
                    5.0
                </span>

            </div>


            <p class="descripcion-detalle">

                <?php echo htmlspecialchars($juego["descripcion"]); ?>

            </p>


            <div class="informacion-extra">

                <div>

                    <span class="titulo-dato">
                        Plataforma
                    </span>

                    <strong>

                        <?php echo htmlspecialchars($juego["plataforma"]); ?>

                    </strong>

                </div>


                <div>

                    <span class="titulo-dato">
                        Categoría
                    </span>

                    <strong>

                        <?php echo htmlspecialchars($juego["categoria"]); ?>

                    </strong>

                </div>


                <div>

                    <span class="titulo-dato">
                        Desarrollador
                    </span>

                    <strong>

                        <?php echo htmlspecialchars($juego["desarrollador"]); ?>

                    </strong>

                </div>


                <div>

                    <span class="titulo-dato">
                        Tipo
                    </span>

                    <strong>

                        <?php echo htmlspecialchars($juego["tipo"]); ?>

                    </strong>

                </div>

            </div>



            <!-- DISPONIBILIDAD -->

            <div class="disponibilidad">

                <span class="punto-disponible"></span>

                Disponible

                <strong>

                    <?php echo $juego["stock"]; ?>

                    unidades

                </strong>

            </div>



            <!-- PRECIO -->

            <div class="zona-precio">

                <span>
                    Precio
                </span>

                <h2>

                    $<?php echo number_format($juego["precio"], 2); ?>

                </h2>

            </div>



            <!-- CANTIDAD -->

            <div class="zona-cantidad">

                <label>
                    Cantidad
                </label>


                <div class="control-cantidad">

                    <button
                        type="button"
                        id="restarCantidad">

                        −

                    </button>


                    <input
                        type="number"
                        id="cantidadJuego"
                        value="1"
                        min="1"
                        max="<?php echo $juego["stock"]; ?>"
                        readonly>


                    <button
                        type="button"
                        id="sumarCantidad">

                        +

                    </button>

                </div>

            </div>



            <!-- BOTÓN CARRITO -->

            <button
                type="button"
                class="boton-agregar-detalle"
                id="agregarCarritoDetalle"

                data-nombre="<?php echo htmlspecialchars($juego["nombre"]); ?>"

                data-precio="<?php echo $juego["precio"]; ?>"

                data-imagen="<?php echo htmlspecialchars($juego["imagen"]); ?>"

                data-id="<?php echo $id; ?>"

                data-stock="<?php echo $juego["stock"]; ?>">

                🛒 Agregar al carrito

            </button>


            <p class="mensaje-compra">

                El pedido se confirmará posteriormente
                desde el módulo correspondiente.

            </p>


        </div>

    </section>



    <!-- INFORMACIÓN INFERIOR -->

    <section class="seccion-inferior">


        <div class="tarjeta-info">

            <span>
                ⚡
            </span>

            <div>

                <h3>
                    Entrega rápida
                </h3>

                <p>
                    Accede rápidamente a la información
                    relacionada con tu compra.
                </p>

            </div>

        </div>


        <div class="tarjeta-info">

            <span>
                🔒
            </span>

            <div>

                <h3>
                    Compra segura
                </h3>

                <p>
                    La información de tu compra se mantiene
                    protegida dentro del sistema.
                </p>

            </div>

        </div>


        <div class="tarjeta-info">

            <span>
                🎮
            </span>

            <div>

                <h3>
                    Varias plataformas
                </h3>

                <p>
                    Encuentra títulos para PC,
                    PlayStation, Xbox y Nintendo.
                </p>

            </div>

        </div>


    </section>


</main>



<!-- ============================
     PIE DE PÁGINA
============================ -->

<footer class="pie-pagina">

    <div>

        <h2>
            GAMEKEY STORE
        </h2>

        <p>
            Proyecto académico de venta de videojuegos.
        </p>

    </div>


    <p>
        © 2026 GAMEKEY STORE
    </p>

</footer>



<script src="../js/detalle.js"></script>

</body>

</html>