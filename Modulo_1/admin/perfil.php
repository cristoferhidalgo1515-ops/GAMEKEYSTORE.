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

include("../config/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

$consulta = $conexion->prepare(
    "SELECT
        nombres,
        apellidos,
        correo,
        telefono,
        direccion,
        rol,
        estado,
        fecha_registro
    FROM usuarios
    WHERE id_usuario = ?
    LIMIT 1"
);

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {

    $consulta->close();
    $conexion->close();

    session_destroy();

    header(
        "Location: ../login.php?mensaje=" .
        urlencode("No se encontró la cuenta.")
    );

    exit();
}

$usuario = $resultado->fetch_assoc();

$consulta->close();
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mi perfil | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/perfil.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado">

            <div>

                <h1>Mi perfil</h1>

                <p>
                    Administra los datos de tu cuenta.
                </p>

            </div>

            <a href="dashboard.php" class="boton-volver">
                Volver al inicio
            </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-perfil">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="perfil-grid">

            <div class="panel-perfil">

                <div class="perfil-cabecera">

                    <div class="avatar">

                        <?php
                        echo strtoupper(
                            substr($usuario["nombres"], 0, 1)
                        );
                        ?>

                    </div>

                    <div>

                        <h2>

                            <?php
                            echo htmlspecialchars(
                                $usuario["nombres"] . " " .
                                $usuario["apellidos"]
                            );
                            ?>

                        </h2>

                        <p>

                            <?php
                            echo ucfirst(
                                htmlspecialchars($usuario["rol"])
                            );
                            ?>

                        </p>

                    </div>

                </div>

                <div class="datos-cuenta">

                    <p>

                        <strong>Correo:</strong>

                        <?php
                        echo htmlspecialchars($usuario["correo"]);
                        ?>

                    </p>

                    <p>

                        <strong>Estado:</strong>

                        <?php
                        echo ucfirst(
                            htmlspecialchars($usuario["estado"])
                        );
                        ?>

                    </p>

                    <p>

                        <strong>Fecha de registro:</strong>

                        <?php
                        echo date(
                            "d/m/Y",
                            strtotime($usuario["fecha_registro"])
                        );
                        ?>

                    </p>

                </div>

            </div>

            <div class="panel-formulario">

                <h2>Información personal</h2>

                <form
                    action="actualizar_perfil.php"
                    method="POST"
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
                                value="<?php echo htmlspecialchars($usuario["nombres"]); ?>"
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
                                value="<?php echo htmlspecialchars($usuario["apellidos"]); ?>"
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
                            value="<?php echo htmlspecialchars($usuario["correo"]); ?>"
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
                            value="<?php echo htmlspecialchars($usuario["telefono"] ?? ""); ?>"
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
                        ><?php echo htmlspecialchars($usuario["direccion"] ?? ""); ?></textarea>

                    </div>

                    <button type="submit" class="boton-guardar">
                        Actualizar información
                    </button>

                </form>

            </div>

        </section>

        <section class="panel-contrasena">

            <h2>Cambiar contraseña</h2>

            <p>
                Escribe tu contraseña actual y después la nueva contraseña.
            </p>

            <form
                action="actualizar_contrasena.php"
                method="POST"
            >

                <div class="campo">

                    <label for="contrasena_actual">
                        Contraseña actual
                    </label>

                    <input
                        type="password"
                        id="contrasena_actual"
                        name="contrasena_actual"
                        required
                    >

                </div>

                <div class="grupo-doble">

                    <div class="campo">

                        <label for="nueva_contrasena">
                            Nueva contraseña
                        </label>

                        <input
                            type="password"
                            id="nueva_contrasena"
                            name="nueva_contrasena"
                            minlength="8"
                            required
                        >

                    </div>

                    <div class="campo">

                        <label for="confirmar_contrasena">
                            Confirmar nueva contraseña
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

                <button type="submit" class="boton-contrasena">
                    Cambiar contraseña
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>