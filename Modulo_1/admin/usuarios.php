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

$busqueda = trim($_GET["busqueda"] ?? "");

if ($busqueda !== "") {

    $texto_busqueda = "%" . $busqueda . "%";

    $consulta = $conexion->prepare(
        "SELECT
            id_usuario,
            nombres,
            apellidos,
            correo,
            telefono,
            rol,
            estado,
            fecha_registro
        FROM usuarios
        WHERE nombres LIKE ?
           OR apellidos LIKE ?
           OR correo LIKE ?
        ORDER BY id_usuario DESC"
    );

    $consulta->bind_param(
        "sss",
        $texto_busqueda,
        $texto_busqueda,
        $texto_busqueda
    );

    $consulta->execute();
    $resultado = $consulta->get_result();

} else {

    $resultado = $conexion->query(
        "SELECT
            id_usuario,
            nombres,
            apellidos,
            correo,
            telefono,
            rol,
            estado,
            fecha_registro
        FROM usuarios
        ORDER BY id_usuario DESC"
    );
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

    <title>Usuarios | GameKey Store</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/usuarios.css">

</head>

<body>

<div class="contenedor">

    <?php include("menu.php"); ?>

    <main class="contenido">

        <header class="encabezado">

            <div>

                <h1>Gestión de usuarios</h1>

                <p>
                    Administra las cuentas registradas en el sistema.
                </p>

            </div  class="acciones-encabezado">

            <a href="crear_usuario.php" class="boton-nuevo">
        + Nuevo usuario
    </a>

    <a href="dashboard.php" class="boton-volver">
        Volver al inicio
    </a>

        </header>

        <?php if (isset($_GET["mensaje"])) { ?>

            <div class="mensaje-resultado">

                <?php
                echo htmlspecialchars($_GET["mensaje"]);
                ?>

            </div>

        <?php } ?>

        <section class="panel-usuarios">

            <form
                action="usuarios.php"
                method="GET"
                class="formulario-busqueda"
            >

                <input
                    type="text"
                    name="busqueda"
                    placeholder="Buscar por nombre, apellido o correo"
                    value="<?php echo htmlspecialchars($busqueda); ?>"
                >

                <button type="submit">
                    Buscar
                </button>

                <?php if ($busqueda !== "") { ?>

                    <a href="usuarios.php">
                        Limpiar
                    </a>

                <?php } ?>

            </form>

            <div class="tabla-contenedor">

                <table>

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($resultado && $resultado->num_rows > 0) { ?>

                        <?php while ($usuario = $resultado->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php
                                    echo $usuario["id_usuario"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $usuario["nombres"] . " " .
                                        $usuario["apellidos"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $usuario["correo"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $usuario["telefono"] ?: "Sin teléfono"
                                    );
                                    ?>
                                </td>

                                <td>

                                    <span class="rol rol-<?php echo $usuario["rol"]; ?>">

                                        <?php
                                        echo ucfirst($usuario["rol"]);
                                        ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="estado estado-<?php echo $usuario["estado"]; ?>">

                                        <?php
                                        echo ucfirst($usuario["estado"]);
                                        ?>

                                    </span>

                                </td>

                                <td>

                                    <?php
                                    echo date(
                                        "d/m/Y",
                                        strtotime($usuario["fecha_registro"])
                                    );
                                    ?>

                                </td>

                                <td class="acciones">

                                    <a
                                        href="editar_usuario.php?id=<?php echo $usuario["id_usuario"]; ?>"
                                        class="boton-editar"
                                    >
                                        Editar
                                    </a>

                                    <?php if (
                                        $usuario["id_usuario"] !=
                                        $_SESSION["id_usuario"]
                                    ) { ?>

                                        <a
                                            href="cambiar_estado_usuario.php?id=<?php echo $usuario["id_usuario"]; ?>"
                                            class="boton-estado"
                                            onclick="return confirm('¿Deseas cambiar el estado de este usuario?');"
                                        >

                                            <?php
                                            echo $usuario["estado"] === "activo"
                                                ? "Desactivar"
                                                : "Activar";
                                            ?>

                                        </a>
                                        <form
            action="eliminar_usuario.php"
            method="POST"
            class="formulario-eliminar"
            onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">

            <input
                type="hidden"
                name="id_usuario"
                value="<?php echo $usuario["id_usuario"]; ?>" >

            <button type="submit" class="boton-eliminar">
                Eliminar
            </button>

        </form>

                                    <?php } else { ?>

                                        <span class="cuenta-actual">
                                            Tu cuenta
                                        </span>

                                    <?php } ?>
                                    
                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td colspan="8" class="sin-resultados">

                                No se encontraron usuarios.

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>

</html>