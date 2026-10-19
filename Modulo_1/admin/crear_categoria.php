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

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nueva categoría | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/formulario_categoria.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-formulario">

            <div>

                <h1>Nueva categoría</h1>

                <p>
                    Registra una nueva categoría para los videojuegos.
                </p>

            </div>

            <a href="categorias.php" class="boton-volver">
                Volver a categorías
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="panel-formulario-categoria">

            <form
                action="guardar_categoria.php"
                method="POST"
            >

                <div class="campo-formulario">

                    <label for="nombre">
                        Nombre de la categoría
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        placeholder="Ejemplo: Estrategia"
                        required
                    >

                </div>

                <div class="campo-formulario">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="255"
                        placeholder="Describe brevemente esta categoría..."
                    ></textarea>

                </div>

                <div class="campo-formulario">

                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        required
                    >

                        <option value="activa">
                            Activa
                        </option>

                        <option value="inactiva">
                            Inactiva
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="boton-guardar-categoria"
                >
                    Guardar categoría
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>