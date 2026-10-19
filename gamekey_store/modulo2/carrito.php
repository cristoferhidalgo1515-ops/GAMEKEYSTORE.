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

    <title>Carrito | GAMEKEY STORE</title>

    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/carrito.css">
</head>

<body>

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

        <nav class="menu" id="menuPrincipal">

            <a href="index.php">
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

        <div class="acciones">

            <a href="#" class="cuenta">
                👤 Mi cuenta
            </a>

            <a href="carrito.php"
               class="carrito">

                🛒 Carrito

                <span id="cantidadCarrito">
                    0
                </span>

            </a>

        </div>

    </header>


    <main class="pagina-carrito">

        <section class="encabezado-carrito">

            <div>

                <p class="subtitulo-carrito">
                    TU COMPRA
                </p>

                <h1>
                    Carrito de compras
                </h1>

                <p>
                    Revisa los videojuegos agregados
                    antes de continuar con tu pedido.
                </p>

            </div>

        </section>


        <section class="zona-carrito">

            <!-- PRODUCTOS -->
            <section class="productos-carrito">

                <div class="barra-carrito">

                    <h2>
                        Productos
                    </h2>

                    <button
                        type="button"
                        id="vaciarCarrito"
                        class="boton-vaciar">

                        Vaciar carrito

                    </button>

                </div>


                <div id="listaCarrito">

                    <!-- JavaScript agregará aquí los productos -->

                </div>


                <div
                    class="carrito-vacio"
                    id="carritoVacio">

                    <span>
                        🛒
                    </span>

                    <h2>
                        Tu carrito está vacío
                    </h2>

                    <p>
                        Agrega videojuegos desde nuestro catálogo.
                    </p>

                    <a
                        href="catalogo.php"
                        class="boton-ir-catalogo">

                        Ver catálogo

                    </a>

                </div>

            </section>


            <!-- RESUMEN -->
            <aside class="resumen-carrito">

                <h2>
                    Resumen de compra
                </h2>


                <div class="fila-resumen">

                    <span>
                        Productos
                    </span>

                    <strong id="cantidadResumen">
                        0
                    </strong>

                </div>


                <div class="fila-resumen">

                    <span>
                        Subtotal
                    </span>

                    <strong id="subtotalCarrito">
                        $0.00
                    </strong>

                </div>


                <div class="fila-resumen">

                    <span>
                        Envío
                    </span>

                    <strong>
                        $0.00
                    </strong>

                </div>


                <hr>


                <div class="fila-total">

                    <span>
                        Total
                    </span>

                    <strong id="totalCarrito">
                        $0.00
                    </strong>

                </div>


                <button
                    type="button"
                    id="continuarCompra"
                    class="boton-continuar">

                    Continuar compra

                </button>


                <p class="nota-carrito">

                    El siguiente paso será conectado
                    posteriormente con el módulo de pedidos.

                </p>

            </aside>

        </section>


        <section class="acciones-inferiores">

            <a
                href="catalogo.php"
                class="seguir-comprando">

                ← Seguir comprando

            </a>

        </section>

    </main>


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


    <script src="../js/carrito.js"></script>

</body>

</html>