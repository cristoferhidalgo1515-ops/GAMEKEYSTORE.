<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'gamekey_store') { header('Location: ../../index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catálogo | GAMEKEY STORE</title>

    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/catalogo.css">
</head>

<body>

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

            <a href="catalogo.php" class="activo">
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

            <a href="#" class="cuenta">
                👤 Mi cuenta
            </a>

            <a href="carrito.php" class="carrito">
                🛒 Carrito
                <span id="cantidadCarrito">0</span>
            </a>

        </div>

    </header>

    <main class="pagina-catalogo">

        <section class="encabezado-catalogo">

            <div>
                <p class="subtitulo-catalogo">
                    EXPLORA NUESTRA COLECCIÓN
                </p>

                <h2>Catálogo de videojuegos</h2>

                <p>
                    Busca y filtra juegos por nombre,
                    plataforma o categoría.
                </p>
            </div>

        </section>

        <section class="zona-catalogo">

            <!-- FILTROS -->
            <aside class="panel-filtros">

                <h3>Filtros</h3>

                <div class="grupo-filtro">

                    <label for="buscadorCatalogo">
                        Buscar videojuego
                    </label>

                    <input
                        type="search"
                        id="buscadorCatalogo"
                        placeholder="Ejemplo: Elden Ring">

                </div>

                <div class="grupo-filtro">

                    <label for="filtroPlataforma">
                        Plataforma
                    </label>

                    <select id="filtroPlataforma">

                        <option value="todos">
                            Todas
                        </option>

                        <option value="pc">
                            PC
                        </option>

                        <option value="playstation">
                            PlayStation
                        </option>

                        <option value="xbox">
                            Xbox
                        </option>

                        <option value="nintendo">
                            Nintendo
                        </option>

                    </select>

                </div>

                <div class="grupo-filtro">

                    <label for="filtroCategoria">
                        Categoría
                    </label>

                    <select id="filtroCategoria">

                        <option value="todos">
                            Todas
                        </option>

                        <option value="accion">
                            Acción
                        </option>

                        <option value="aventura">
                            Aventura
                        </option>

                        <option value="rpg">
                            RPG
                        </option>

                        <option value="carreras">
                            Carreras
                        </option>

                        <option value="deportes">
                            Deportes
                        </option>

                        <option value="terror">
                            Terror
                        </option>

                    </select>

                </div>

                <div class="grupo-filtro">

                    <label for="ordenarCatalogo">
                        Ordenar por
                    </label>

                    <select id="ordenarCatalogo">

                        <option value="normal">
                            Recomendados
                        </option>

                        <option value="precio-menor">
                            Precio: menor a mayor
                        </option>

                        <option value="precio-mayor">
                            Precio: mayor a menor
                        </option>

                        <option value="nombre">
                            Nombre: A-Z
                        </option>

                    </select>

                </div>

                <button
                    type="button"
                    id="limpiarFiltros"
                    class="boton-limpiar">
                    Limpiar filtros
                </button>

            </aside>

            <!-- CONTENIDO -->
            <section class="contenido-catalogo">

                <div class="barra-resultados">

                    <p>
                        Juegos encontrados:
                        <strong id="cantidadResultados">6</strong>
                    </p>

                </div>

                <div
                    class="cuadricula-catalogo"
                    id="cuadriculaCatalogo">

                    <!-- ===================== -->
                    <!-- SPIDER-MAN 2 -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="Marvel Spider-Man 2"
                        data-plataforma="playstation"
                        data-categoria="accion"
                        data-precio="49.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma ps">
                                PS5
                            </span>

                            <img
                                src="../img/juegos/spiderman2.jpg"
                                alt="Marvel's Spider-Man 2">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                Acción · PlayStation
                            </p>

                            <h3>
                                Marvel's Spider-Man 2
                            </h3>

                            <p class="descripcion-producto">
                                Explora una gran ciudad y enfrenta
                                nuevos enemigos.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $49.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=1"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="Marvel's Spider-Man 2"
                                data-precio="49.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                    <!-- ===================== -->
                    <!-- ELDEN RING -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="Elden Ring"
                        data-plataforma="pc"
                        data-categoria="rpg"
                        data-precio="39.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma pc">
                                PC
                            </span>

                            <img
                                src="../img/juegos/eldenring.jpg"
                                alt="Elden Ring">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                RPG · PC
                            </p>

                            <h3>
                                Elden Ring
                            </h3>

                            <p class="descripcion-producto">
                                Recorre un mundo abierto lleno
                                de desafíos y secretos.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $39.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=2"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="Elden Ring"
                                data-precio="39.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                    <!-- ===================== -->
                    <!-- GOD OF WAR -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="God of War Ragnarok"
                        data-plataforma="playstation"
                        data-categoria="aventura"
                        data-precio="49.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma ps">
                                PS5
                            </span>

                            <img
                                src="../img/juegos/gow.jpg"
                                alt="God of War Ragnarök">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                Aventura · PlayStation
                            </p>

                            <h3>
                                God of War Ragnarök
                            </h3>

                            <p class="descripcion-producto">
                                Vive una aventura mitológica
                                llena de acción.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $49.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=3"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="God of War Ragnarök"
                                data-precio="49.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                    <!-- ===================== -->
                    <!-- FORZA HORIZON 5 -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="Forza Horizon 5"
                        data-plataforma="xbox"
                        data-categoria="carreras"
                        data-precio="44.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma xbox">
                                XBOX
                            </span>

                            <img
                                src="../img/juegos/forza5.jpg"
                                alt="Forza Horizon 5">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                Carreras · Xbox
                            </p>

                            <h3>
                                Forza Horizon 5
                            </h3>

                            <p class="descripcion-producto">
                                Compite en diferentes eventos y
                                explora carreteras increíbles.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $44.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=4"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="Forza Horizon 5"
                                data-precio="44.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                    <!-- ===================== -->
                    <!-- EA SPORTS FC 26 -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="EA Sports FC 26"
                        data-plataforma="playstation"
                        data-categoria="deportes"
                        data-precio="59.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma ps">
                                PS5
                            </span>

                            <img
                                src="../img/juegos/fifa.jpeg"
                                alt="EA Sports FC 26">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                Deportes · PlayStation
                            </p>

                            <h3>
                                EA Sports FC 26
                            </h3>

                            <p class="descripcion-producto">
                                Disfruta partidos, equipos y
                                modos de juego de fútbol.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $59.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=5"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="EA Sports FC 26"
                                data-precio="59.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                    <!-- ===================== -->
                    <!-- RESIDENT EVIL 4 -->
                    <!-- ===================== -->

                    <article
                        class="producto-catalogo"
                        data-nombre="Resident Evil 4"
                        data-plataforma="pc"
                        data-categoria="terror"
                        data-precio="34.99">

                        <div class="portada-catalogo">

                            <span class="etiqueta-plataforma pc">
                                PC
                            </span>

                            <img
                                src="../img/juegos/re4.jpg"
                                alt="Resident Evil 4">

                        </div>

                        <div class="datos-producto">

                            <p class="tipo-producto">
                                Terror · PC
                            </p>

                            <h3>
                                Resident Evil 4
                            </h3>

                            <p class="descripcion-producto">
                                Sobrevive a una misión peligrosa
                                llena de misterio.
                            </p>

                            <div class="pie-producto">

                                <p class="precio-producto">
                                    $34.99
                                </p>

                                <a
                                    href="detalle_juego.php?id=6"
                                    class="boton-detalle">
                                    Ver detalle
                                </a>

                            </div>

                            <button
                                type="button"
                                class="boton-comprar agregar-catalogo"
                                data-nombre="Resident Evil 4"
                                data-precio="34.99">
                                Agregar al carrito
                            </button>

                        </div>

                    </article>

                </div>

                <p
                    class="sin-resultados"
                    id="sinResultados">
                    No se encontraron videojuegos con esos filtros.
                </p>

            </section>

        </section>

    </main>

    <footer class="pie-pagina">

        <div>
            <h2>GAMEKEY STORE</h2>
            <p>
                Proyecto académico de venta de videojuegos.
            </p>
        </div>

        <p>
            © 2026 GAMEKEY STORE
        </p>

    </footer>

    <script src="../js/catalogo.js"></script>

</body>

</html>