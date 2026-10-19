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

    <title>GAMEKEY STORE</title>

    <!-- Archivo de estilos -->
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="encabezado">

        <a href="index.php" class="logo">
            <span class="logo-icono">🎮</span>

            <div>
                <h1>GAMEKEY</h1>
                <p>STORE</p>
            </div>
        </a>

        <!-- Botón para móviles -->
        <button
            type="button"
            class="boton-menu"
            id="botonMenu"
            aria-label="Abrir menú">
            ☰
        </button>

        <!-- MENÚ PRINCIPAL -->
        <nav class="menu" id="menuPrincipal">

            <a href="index.php" class="activo">
                Inicio
            </a>

            <a href="catalogo.php">
                Juegos
            </a>

            <a href="categorias.php">
                Categorías
            </a>


<a href="soporte.php">
    Soporte
</a>

        </nav>

        <!-- ACCIONES -->
        <div class="acciones">

            <div class="buscador">
                <input
                    type="search"
                    id="buscadorInicio"
                    placeholder="Buscar juegos..."
                    aria-label="Buscar juegos">

                <button
                    type="button"
                    id="botonBuscar"
                    aria-label="Buscar">
                    🔍
                </button>
            </div>

            <!-- Después se conectará al login del módulo 1 -->
            <a href="#" class="cuenta">
                👤 Mi cuenta
            </a>

            <a href="carrito.php" class="carrito">
                🛒 Carrito
                <span id="cantidadCarrito">0</span>
            </a>

        </div>

    </header>

    <main>

        <!-- BANNER PRINCIPAL -->
        <section class="banner">

            <div class="banner-contenido">

                <p class="texto-pequeno">
                    BIENVENIDO A
                </p>

                <h2>
                    <span>GAMEKEY</span> STORE
                </h2>

                <h3>
                    TU TIENDA DE VIDEOJUEGOS
                </h3>

                <p class="descripcion-banner">
                    Encuentra videojuegos para PC, PlayStation,
                    Xbox y Nintendo desde una sola página.
                </p>

                <a href="catalogo.php" class="boton-principal">
                    VER CATÁLOGO
                    <span>➜</span>
                </a>

            </div>

            <div class="banner-control">
                🎮
            </div>

        </section>

        <!-- BENEFICIOS -->
        <section class="beneficios">

            <article class="beneficio">
                <span class="beneficio-icono">⚡</span>

                <div>
                    <h3>ENTREGA INMEDIATA</h3>
                    <p>Recibe la información de tu compra rápidamente.</p>
                </div>
            </article>

            <article class="beneficio">
                <span class="beneficio-icono">🛡️</span>

                <div>
                    <h3>COMPRA SEGURA</h3>
                    <p>Tus datos estarán protegidos dentro del sistema.</p>
                </div>
            </article>

            <article class="beneficio">
                <span class="beneficio-icono">🏷️</span>

                <div>
                    <h3>MEJORES OFERTAS</h3>
                    <p>Encuentra descuentos en juegos seleccionados.</p>
                </div>
            </article>

            <article class="beneficio" id="soporte">
                <span class="beneficio-icono">🎧</span>

                <div>
                    <h3>SOPORTE</h3>
                    <p>Consulta tus dudas sobre productos y compras.</p>
                </div>
            </article>

        </section>

        <!-- JUEGOS DESTACADOS -->
        <section class="seccion-juegos">

            <div class="titulo-seccion">

                <div>
                    <span>🎮</span>
                    <h2>JUEGOS DESTACADOS</h2>
                </div>

                <a href="catalogo.php">
                    Ver todos ➜
                </a>

            </div>

            <div class="contenedor-juegos">

                <!-- JUEGO 1 -->
                <article class="tarjeta-juego">

                    <div class="imagen-juego imagen-ejemplo">
                        <span class="plataforma ps">
                            PS5
                        </span>

                        <span class="texto-imagen">
                            SPIDER-MAN 2
                        </span>
                    </div>

                    <div class="informacion-juego">

                        <h3>Marvel's Spider-Man 2</h3>

                        <p class="categoria-juego">
                            Acción y aventura
                        </p>

                        <p class="precio">
                            $49.99
                        </p>

                        <button
                            type="button"
                            class="boton-comprar"
                            data-nombre="Marvel's Spider-Man 2"
                            data-precio="49.99">
                            AGREGAR AL CARRITO
                        </button>

                    </div>

                </article>

                <!-- JUEGO 2 -->
                <article class="tarjeta-juego">

                    <div class="imagen-juego imagen-ejemplo">
                        <span class="plataforma pc">
                            PC
                        </span>

                        <span class="texto-imagen">
                            ELDEN RING
                        </span>
                    </div>

                    <div class="informacion-juego">

                        <h3>Elden Ring</h3>

                        <p class="categoria-juego">
                            RPG y mundo abierto
                        </p>

                        <p class="precio">
                            $39.99
                        </p>

                        <button
                            type="button"
                            class="boton-comprar"
                            data-nombre="Elden Ring"
                            data-precio="39.99">
                            AGREGAR AL CARRITO
                        </button>

                    </div>

                </article>

                <!-- JUEGO 3 -->
                <article class="tarjeta-juego">

                    <div class="imagen-juego imagen-ejemplo">
                        <span class="plataforma ps">
                            PS5
                        </span>

                        <span class="texto-imagen">
                            GOD OF WAR
                        </span>
                    </div>

                    <div class="informacion-juego">

                        <h3>God of War Ragnarök</h3>

                        <p class="categoria-juego">
                            Acción y aventura
                        </p>

                        <p class="precio">
                            $49.99
                        </p>

                        <button
                            type="button"
                            class="boton-comprar"
                            data-nombre="God of War Ragnarök"
                            data-precio="49.99">
                            AGREGAR AL CARRITO
                        </button>

                    </div>

                </article>

                <!-- JUEGO 4 -->
                <article class="tarjeta-juego">

                    <div class="imagen-juego imagen-ejemplo">
                        <span class="plataforma xbox">
                            XBOX
                        </span>

                        <span class="texto-imagen">
                            FORZA HORIZON 5
                        </span>
                    </div>

                    <div class="informacion-juego">

                        <h3>Forza Horizon 5</h3>

                        <p class="categoria-juego">
                            Carreras
                        </p>

                        <p class="precio">
                            $44.99
                        </p>

                        <button
                            type="button"
                            class="boton-comprar"
                            data-nombre="Forza Horizon 5"
                            data-precio="44.99">
                            AGREGAR AL CARRITO
                        </button>

                    </div>

                </article>

            </div>

        </section>

        <!-- CATEGORÍAS PRINCIPALES -->
        <section class="seccion-categorias">

            <div class="titulo-seccion">
                <div>
                    <span>🕹️</span>
                    <h2>CATEGORÍAS PRINCIPALES</h2>
                </div>
            </div>

            <div class="contenedor-categorias">

                <a href="categorias.php?categoria=accion"
                   class="tarjeta-categoria">
                    <span>⚔️</span>
                    <h3>Acción</h3>
                </a>

                <a href="categorias.php?categoria=aventura"
                   class="tarjeta-categoria">
                    <span>🗺️</span>
                    <h3>Aventura</h3>
                </a>

                <a href="categorias.php?categoria=carreras"
                   class="tarjeta-categoria">
                    <span>🏎️</span>
                    <h3>Carreras</h3>
                </a>

                <a href="categorias.php?categoria=deportes"
                   class="tarjeta-categoria">
                    <span>⚽</span>
                    <h3>Deportes</h3>
                </a>

                <a href="categorias.php?categoria=terror"
                   class="tarjeta-categoria">
                    <span>👻</span>
                    <h3>Terror</h3>
                </a>

            </div>

        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie-pagina">

        <div>
            <h2>GAMEKEY STORE</h2>
            <p>Proyecto académico de venta de videojuegos.</p>
        </div>

        <p>
            © 2026 GAMEKEY STORE
        </p>

    </footer>

    <!-- Archivo JavaScript -->
    <script src="../js/principal.js"></script>

</body>

</html>