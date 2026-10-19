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

    <title>Nueva plataforma | GameKey Store</title>

    <link
        rel="stylesheet"
        href="../css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="../css/formulario_plataforma.css"
    >

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado-formulario-plataforma">

            <div>

                <h1>Nueva plataforma</h1>

                <p>
                    Registra una nueva plataforma para los videojuegos.
                </p>

            </div>

            <a
                href="plataformas.php"
                class="boton-volver-plataforma"
            >
                Volver a plataformas
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario-plataforma">

                <?php

                echo htmlspecialchars(
                    $_GET["mensaje"],
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

        <?php } ?>

        <section class="panel-formulario-plataforma">

            <form
                action="guardar_plataforma.php"
                method="POST"
            >

                <div class="campo-plataforma">

                    <label for="nombre">
                        Nombre de la plataforma
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        placeholder="Ejemplo: Steam"
                        required
                    >

                </div>

                <div class="campo-plataforma">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="255"
                        placeholder="Describe brevemente esta plataforma..."
                    ></textarea>

                </div>

                <div class="campo-plataforma">

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
                    class="boton-guardar-plataforma"
                >
                    Guardar plataforma
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>