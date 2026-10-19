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

    <title>Soporte | GAMEKEY STORE</title>

    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/soporte.css">
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
        id="botonMenu">

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

        <a href="soporte.php" class="activo">
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


<main class="pagina-soporte">

    <section class="titulo-soporte">

        <p>
            ¿NECESITAS AYUDA?
        </p>

        <h1>
            Soporte GAMEKEY
        </h1>

        <span>
            Envíanos tu consulta y trataremos
            de ayudarte.
        </span>

    </section>


    <section class="contenedor-soporte">

        <div class="informacion-soporte">

            <h2>
                ¿En qué podemos ayudarte?
            </h2>

            <p>
                Puedes enviar consultas relacionadas
                con videojuegos, carrito o funcionamiento
                general de la tienda.
            </p>


            <div class="dato-soporte">

                <span>
                    🎮
                </span>

                <div>
                    <h3>
                        Videojuegos
                    </h3>

                    <p>
                        Información sobre juegos
                        y plataformas.
                    </p>
                </div>

            </div>


            <div class="dato-soporte">

                <span>
                    🛒
                </span>

                <div>
                    <h3>
                        Carrito
                    </h3>

                    <p>
                        Ayuda con productos agregados
                        y cantidades.
                    </p>
                </div>

            </div>


            <div class="dato-soporte">

                <span>
                    💬
                </span>

                <div>
                    <h3>
                        Consultas
                    </h3>

                    <p>
                        Envía cualquier duda relacionada
                        con GAMEKEY STORE.
                    </p>
                </div>

            </div>

        </div>


        <form
            class="formulario-soporte"
            id="formularioSoporte">

            <h2>
                Enviar consulta
            </h2>


            <label for="nombre">
                Nombre
            </label>

            <input
                type="text"
                id="nombre"
                placeholder="Escribe tu nombre"
                required>


            <label for="correo">
                Correo electrónico
            </label>

            <input
                type="email"
                id="correo"
                placeholder="correo@ejemplo.com"
                required>


            <label for="asunto">
                Asunto
            </label>

            <select
                id="asunto"
                required>

                <option value="">
                    Selecciona una opción
                </option>

                <option value="producto">
                    Consulta sobre un videojuego
                </option>

                <option value="carrito">
                    Problema con el carrito
                </option>

                <option value="cuenta">
                    Consulta sobre mi cuenta
                </option>

                <option value="otro">
                    Otro
                </option>

            </select>


            <label for="mensaje">
                Mensaje
            </label>

            <textarea
                id="mensaje"
                rows="6"
                placeholder="Escribe tu consulta..."
                required></textarea>


            <button type="submit">

                Enviar consulta

            </button>


            <p
                id="mensajeEnviado"
                class="mensaje-enviado">

                Consulta enviada correctamente.

            </p>

        </form>

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


<script src="../js/soporte.js"></script>

</body>

</html>