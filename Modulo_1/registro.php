<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro | GameKey Store</title>

    <link rel="stylesheet" href="css/registro.css">
</head>

<body>

    <div class="contenedor-registro">

        <div class="formulario">

            <h1>Crear cuenta</h1>
            <p class="subtitulo">Regístrate en GameKey Store</p>

            <?php
            if (isset($_GET["mensaje"])) {
                echo '<div class="mensaje">' .
                    htmlspecialchars($_GET["mensaje"]) .
                    '</div>';
            }
            ?>

            <form action="guardar_usuario.php" method="POST">

                <div class="grupo-doble">

                    <div class="campo">
                        <label for="nombres">Nombres</label>

                        <input
                            type="text"
                            id="nombres"
                            name="nombres"
                            placeholder="Escribe tus nombres"
                            required>
                    </div>

                    <div class="campo">
                        <label for="apellidos">Apellidos</label>

                        <input
                            type="text"
                            id="apellidos"
                            name="apellidos"
                            placeholder="Escribe tus apellidos"
                            required>
                    </div>

                </div>

                <div class="campo">
                    <label for="correo">Correo electrónico</label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="ejemplo@correo.com"
                        required>
                </div>

                <div class="campo">
                    <label for="telefono">Teléfono</label>

                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        placeholder="0000-0000">
                </div>

                <div class="campo">
                    <label for="direccion">Dirección</label>

                    <textarea
                        id="direccion"
                        name="direccion"
                        placeholder="Escribe tu dirección"></textarea>
                </div>

                <div class="campo">
                    <label for="contrasena">Contraseña</label>

                    <input
                        type="password"
                        id="contrasena"
                        name="contrasena"
                        placeholder="Crea una contraseña"
                        minlength="6"
                        required>
                </div>

                <div class="campo">
                    <label for="confirmar_contrasena">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="confirmar_contrasena"
                        name="confirmar_contrasena"
                        placeholder="Repite la contraseña"
                        minlength="6"
                        required>
                </div>

                <button type="submit">
                    Registrarme
                </button>

            </form>

            <p class="enlace-login">
                ¿Ya tienes una cuenta?
                <a href="login.php">Iniciar sesión</a>
            </p>

        </div>

    </div>

</body>

</html>