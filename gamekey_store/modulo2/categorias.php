<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'gamekey_store') { header('Location: ../../index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Categorías | GAMEKEY STORE</title>

    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/categorias.css">
</head>

<body>

    <!-- =========================
         ENCABEZADO
    ========================== -->

    <header class="encabezado">

        <a href="index.php" class="logo">

            <span class="logo-icono">🎮</span>

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


        <nav class="menu" id="menuPrincipal">

            <a href="index.php">
                Inicio
            </a>

            <a href="catalogo.php">
                Juegos
            </a>

            <a href="categorias.php" class="activo">
                Categorías
            </a>



<a href="soporte.php">
    Soporte
</a>
        </nav>


        <div class="acciones">

            <a href="#" class="cuenta">
                👤 Mi cuenta
            </a>

            <a href="carrito.php" class="carrito">

                🛒 Carrito

                <span id="cantidadCarrito">
                    0
                </span>

            </a>

        </div>

    </header>


    <!-- =========================
         CONTENIDO
    ========================== -->

    <main class="pagina-categorias">


        <!-- PRESENTACIÓN -->

        <section class="encabezado-categorias">

            <p class="subtitulo-categorias">
                ENCUENTRA TU PRÓXIMO JUEGO
            </p>

            <h1>
                Explora por categorías
            </h1>

            <p class="descripcion-categorias">

                Descubre videojuegos según el género
                que más te guste.

            </p>

        </section>


        <!-- CATEGORÍAS -->

        <section class="contenedor-categorias">

            <div class="cuadricula-categorias">


                <!-- ACCIÓN -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="accion">

                    <div class="icono-categoria">
                        ⚔️
                    </div>

                    <h2>
                        Acción
                    </h2>

                    <p>
                        Combates, desafíos y aventuras
                        llenas de adrenalina.
                    </p>

                    <span>
                        Marvel's Spider-Man 2
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="accion">

                        Ver juegos

                    </button>

                </article>


                <!-- AVENTURA -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="aventura">

                    <div class="icono-categoria">
                        🗺️
                    </div>

                    <h2>
                        Aventura
                    </h2>

                    <p>
                        Explora mundos, descubre historias
                        y supera grandes desafíos.
                    </p>

                    <span>
                        God of War Ragnarök
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="aventura">

                        Ver juegos

                    </button>

                </article>


                <!-- RPG -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="rpg">

                    <div class="icono-categoria">
                        🛡️
                    </div>

                    <h2>
                        RPG
                    </h2>

                    <p>
                        Mejora personajes y explora
                        enormes mundos.
                    </p>

                    <span>
                        Elden Ring
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="rpg">

                        Ver juegos

                    </button>

                </article>


                <!-- CARRERAS -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="carreras">

                    <div class="icono-categoria">
                        🏎️
                    </div>

                    <h2>
                        Carreras
                    </h2>

                    <p>
                        Velocidad, automóviles y
                        competencias emocionantes.
                    </p>

                    <span>
                        Forza Horizon 5
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="carreras">

                        Ver juegos

                    </button>

                </article>


                <!-- DEPORTES -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="deportes">

                    <div class="icono-categoria">
                        ⚽
                    </div>

                    <h2>
                        Deportes
                    </h2>

                    <p>
                        Compite con tus equipos y
                        deportistas favoritos.
                    </p>

                    <span>
                        EA Sports FC 26
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="deportes">

                        Ver juegos

                    </button>

                </article>


                <!-- TERROR -->

                <article
                    class="tarjeta-categoria"
                    data-categoria="terror">

                    <div class="icono-categoria">
                        👻
                    </div>

                    <h2>
                        Terror
                    </h2>

                    <p>
                        Misterio, supervivencia y
                        experiencias llenas de tensión.
                    </p>

                    <span>
                        Resident Evil 4
                    </span>

                    <button
                        type="button"
                        class="boton-categoria"
                        data-categoria="terror">

                        Ver juegos

                    </button>

                </article>

            </div>


            <!-- BOTÓN GENERAL -->

            <div class="zona-todos">

                <p>
                    ¿Quieres ver todos los videojuegos?
                </p>

                <a
                    href="catalogo.php"
                    class="boton-todos">

                    Ver catálogo completo

                </a>

            </div>

        </section>

    </main>


    <!-- =========================
         FOOTER
    ========================== -->

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


    <script src="../js/categorias.js"></script>

</body>

</html>