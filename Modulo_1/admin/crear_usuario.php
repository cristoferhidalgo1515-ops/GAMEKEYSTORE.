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

    <title>Crear usuario | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/editar_usuario.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado">

            <div>
                <h1>Crear usuario</h1>
                <p>Registra una nueva cuenta en el sistema.</p>
            </div>

            <a href="usuarios.php" class="boton-volver">
                Volver
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-formulario">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="panel-edicion">

            <form
                action="guardar_nuevo_usuario.php"
                method="POST"
                autocomplete="off"
            >

                <div class="grupo-doble">

                    <div class="campo">

                        <label for="nombres">
                            Nombres
                        </label>

                        <input
                            type="text"
                            id="nombres"
                            name="nombres"
                            maxlength="100"
                            required
                        >

                    </div>

                    <div class="campo">

                        <label for="apellidos">
                            Apellidos
                        </label>

                        <input
                            type="text"
                            id="apellidos"
                            name="apellidos"
                            maxlength="100"
                            required
                        >

                    </div>

                </div>

                <div class="campo">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="campo">

                    <label for="telefono">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        maxlength="20"
                        placeholder="Ejemplo: 7000-0000"
                    >

                </div>

                <div class="campo">

                    <label for="direccion">
                        Dirección
                    </label>

                    <textarea
                        id="direccion"
                        name="direccion"
                        maxlength="255"
                    ></textarea>

                </div>

                <div class="grupo-doble">

                    <div class="campo">

                        <label for="rol">
                            Rol
                        </label>

                        <select
                            id="rol"
                            name="rol"
                            required
                        >

                            <option value="">
                                Selecciona un rol
                            </option>

                            <option value="cliente">
                                Cliente
                            </option>

                            <option value="empleado">
                                Empleado
                            </option>

                            <option value="administrador">
                                Administrador
                            </option>

                        </select>

                    </div>

                    <div class="campo">

                        <label for="estado">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado"
                            required
                        >

                            <option value="activo">
                                Activo
                            </option>

                            <option value="inactivo">
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <div class="grupo-doble">

                    <div class="campo">

                        <label for="contrasena">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            id="contrasena"
                            name="contrasena"
                            minlength="8"
                            required
                        >

                    </div>

                    <div class="campo">

                        <label for="confirmar_contrasena">
                            Confirmar contraseña
                        </label>

                        <input
                            type="password"
                            id="confirmar_contrasena"
                            name="confirmar_contrasena"
                            minlength="8"
                            required
                        >

                    </div>

                </div>

                <button type="submit" class="boton-guardar">
                    Crear usuario
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>