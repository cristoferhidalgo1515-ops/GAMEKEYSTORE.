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

$id_usuario = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id_usuario) {
    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("Usuario no válido.")
    );
    exit();
}

$consulta = $conexion->prepare(
    "SELECT
        id_usuario,
        nombres,
        apellidos,
        correo,
        telefono,
        direccion,
        rol,
        estado
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

    header(
        "Location: usuarios.php?mensaje=" .
        urlencode("El usuario no existe.")
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

    <title>Editar usuario | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/editar_usuario.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado">

            <div>
                <h1>Editar usuario</h1>
                <p>Modifica la información de la cuenta.</p>
            </div>

            <a href="usuarios.php" class="boton-volver">
                Volver
            </a>

        </header>

        <section class="panel-edicion">

            <form
                action="actualizar_usuario.php"
                method="POST"
            >

                <input
                    type="hidden"
                    name="id_usuario"
                    value="<?php echo $usuario["id_usuario"]; ?>"
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
                    ><?php echo htmlspecialchars($usuario["direccion"] ?? ""); ?></textarea>

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

                            <option
                                value="cliente"
                                <?php echo $usuario["rol"] === "cliente" ? "selected" : ""; ?>
                            >
                                Cliente
                            </option>

                            <option
                                value="empleado"
                                <?php echo $usuario["rol"] === "empleado" ? "selected" : ""; ?>
                            >
                                Empleado
                            </option>

                            <option
                                value="administrador"
                                <?php echo $usuario["rol"] === "administrador" ? "selected" : ""; ?>
                            >
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

                            <option
                                value="activo"
                                <?php echo $usuario["estado"] === "activo" ? "selected" : ""; ?>
                            >
                                Activo
                            </option>

                            <option
                                value="inactivo"
                                <?php echo $usuario["estado"] === "inactivo" ? "selected" : ""; ?>
                            >
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <button type="submit" class="boton-guardar">
                    Guardar cambios
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>