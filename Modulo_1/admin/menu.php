<?php

$pagina_actual = basename($_SERVER["PHP_SELF"]);

$nombre_admin = $_SESSION["nombres"] ?? "Administrador";
$apellido_admin = $_SESSION["apellidos"] ?? "";

$nombre_completo = trim(
    $nombre_admin . " " . $apellido_admin
);

$inicial = strtoupper(
    substr($nombre_admin, 0, 1)
);

?>

<aside class="menu-lateral">

    <div class="logo">

        <span>🎮</span>

        <h2>GAMEKEY</h2>

        <p>STORE</p>

    </div>

    <nav class="navegacion">

        <!-- INICIO -->

        <a
            href="dashboard.php"
            class="<?php
                echo $pagina_actual === "dashboard.php"
                    ? "activo"
                    : "";
            ?>"
        >
            Inicio
        </a>

        <!-- USUARIOS -->

        <a
            href="usuarios.php"
            class="<?php
                echo in_array(
                    $pagina_actual,
                    [
                        "usuarios.php",
                        "crear_usuario.php",
                        "editar_usuario.php",
                        "guardar_usuario.php",
                        "actualizar_usuario.php"
                    ],
                    true
                )
                    ? "activo"
                    : "";
            ?>"
        >
            Usuarios
        </a>

        <!-- VIDEOJUEGOS -->

        <a
            href="videojuegos.php"
            class="<?php
                echo in_array(
                    $pagina_actual,
                    [
                        "videojuegos.php",
                        "crear_videojuego.php",
                        "editar_videojuego.php",
                        "guardar_videojuego.php",
                        "actualizar_videojuego.php",
                        "cambiar_estado_videojuego.php"
                    ],
                    true
                )
                    ? "activo"
                    : "";
            ?>"
        >
            Videojuegos
        </a>

        <!-- CATEGORÍAS -->

        <a
            href="categorias.php"
            class="<?php
                echo in_array(
                    $pagina_actual,
                    [
                        "categorias.php",
                        "crear_categoria.php",
                        "editar_categoria.php",
                        "guardar_categoria.php",
                        "actualizar_categoria.php"
                    ],
                    true
                )
                    ? "activo"
                    : "";
            ?>"
        >
            Categorías
        </a>

        <!-- PLATAFORMAS -->

        <a
            href="plataformas.php"
            class="<?php
                echo in_array(
                    $pagina_actual,
                    [
                        "plataformas.php",
                        "crear_plataforma.php",
                        "editar_plataforma.php",
                        "guardar_plataforma.php",
                        "actualizar_plataforma.php"
                    ],
                    true
                )
                    ? "activo"
                    : "";
            ?>"
        >
            Plataformas
        </a>

        <!-- INVENTARIO -->

        <a
            href="inventario.php"
            class="<?php
                echo in_array(
                    $pagina_actual,
                    [
                        "inventario.php",
                        "editar_stock.php",
                        "actualizar_stock.php"
                    ],
                    true
                )
                    ? "activo"
                    : "";
            ?>"
        >
            Inventario
        </a>

        <!-- PEDIDOS -->

        <a
            href="pedidos.php"
            class="<?php echo $pagina_actual === 'pedidos.php' ? 'activo' : ''; ?>"
        >
            Pedidos
        </a>

        <!-- CÓDIGOS QR -->

        <a
            href="#"
            class="enlace-pendiente"
        >
            Códigos QR
        </a>

    </nav>

    <div class="zona-cuenta">

        <button
            type="button"
            class="boton-cuenta"
            id="botonCuenta"
        >

            <div class="cuenta-avatar">

                <?php

                echo htmlspecialchars(
                    $inicial,
                    ENT_QUOTES,
                    "UTF-8"
                );

                ?>

            </div>

            <div class="cuenta-informacion">

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $nombre_completo,
                        ENT_QUOTES,
                        "UTF-8"
                    );

                    ?>

                </strong>

                <span>Administrador</span>

            </div>

            <span
                class="flecha-cuenta"
                id="flechaCuenta"
            >
                ›
            </span>

        </button>

        <div
            class="menu-cuenta"
            id="menuCuenta"
        >

            <div class="menu-cuenta-encabezado">

                <div class="cuenta-avatar grande">

                    <?php

                    echo htmlspecialchars(
                        $inicial,
                        ENT_QUOTES,
                        "UTF-8"
                    );

                    ?>

                </div>

                <div>

                    <strong>

                        <?php

                        echo htmlspecialchars(
                            $nombre_completo,
                            ENT_QUOTES,
                            "UTF-8"
                        );

                        ?>

                    </strong>

                    <span>Administrador</span>

                </div>

            </div>

            <div class="separador-cuenta"></div>

            <a
                href="perfil.php"
                class="opcion-cuenta"
            >

                <span class="icono-cuenta">
                    👤
                </span>

                <span>
                    Mi perfil
                </span>

            </a>

            <div class="separador-cuenta"></div>

            <a
                href="../logout.php"
                class="opcion-cuenta cerrar-cuenta"
            >

                <span class="icono-cuenta">
                    ↪
                </span>

                <span>
                    Cerrar sesión
                </span>

            </a>

        </div>

    </div>

</aside>

<script>

const botonCuenta =
    document.getElementById("botonCuenta");

const menuCuenta =
    document.getElementById("menuCuenta");

const flechaCuenta =
    document.getElementById("flechaCuenta");

if (
    botonCuenta &&
    menuCuenta &&
    flechaCuenta
) {

    botonCuenta.addEventListener(
        "click",
        function (evento) {

            evento.stopPropagation();

            menuCuenta.classList.toggle(
                "mostrar"
            );

            flechaCuenta.classList.toggle(
                "girada"
            );

        }
    );

    menuCuenta.addEventListener(
        "click",
        function (evento) {

            evento.stopPropagation();

        }
    );

    document.addEventListener(
        "click",
        function () {

            menuCuenta.classList.remove(
                "mostrar"
            );

            flechaCuenta.classList.remove(
                "girada"
            );

        }
    );

}

</script>