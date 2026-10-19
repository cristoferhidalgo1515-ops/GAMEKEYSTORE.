<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["rol"] !== "cliente") {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cliente | GameKey Store</title>
</head>

<body>

    <h1>
        Bienvenido,
        <?php echo htmlspecialchars($_SESSION["nombres"]); ?>
    </h1>

    <p>Has iniciado sesión como cliente.</p>

    <p>
        La página principal, el catálogo y el carrito serán desarrollados
        en el Módulo 2.
    </p>

    <a href="logout.php">Cerrar sesión</a>

</body>

</html>