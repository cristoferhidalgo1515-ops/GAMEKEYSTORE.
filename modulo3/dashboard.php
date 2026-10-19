<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'modulo3') { header('Location: ../../index.php'); exit; }

require_once "../config/conexion.php";

$consultaPedidos = $conn->query(
    "SELECT COUNT(*) AS total FROM pedidos"
);
$totalPedidos = $consultaPedidos->fetch_assoc()["total"];

$consultaJuegos = $conn->query(
    "SELECT COUNT(*) AS total FROM videojuegos"
);
$totalJuegos = $consultaJuegos->fetch_assoc()["total"];

$consultaQR = $conn->query(
    "SELECT COUNT(*) AS total FROM codigos_qr"
);
$totalQR = $consultaQR->fetch_assoc()["total"];

$consultaFacturas = $conn->query(
    "SELECT COUNT(*) AS total FROM facturas"
);
$totalFacturas = $consultaFacturas->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard | Gamekey Store</title>

    <link
        rel="stylesheet"
        href="../css/estilos.css?v=2"
    >
</head>

<body>

    <main class="dashboard">

        <header class="dashboard-encabezado">

            <h1>🎮 GAMEKEY STORE</h1>

            <p>
                Módulo 3 · Gestión de pedidos, inventario,
                QR y facturas
            </p>

        </header>

        <section class="cards">

            <article class="card">

                <h2>📦 Pedidos</h2>

                <h3>
                    <?php echo $totalPedidos; ?>
                </h3>

                <a href="pedidos/index.php">
                    Entrar
                </a>

            </article>

            <article class="card">

                <h2>🎮 Inventario</h2>

                <h3>
                    <?php echo $totalJuegos; ?>
                </h3>

                <a href="inventario/index.php">
                    Entrar
                </a>

            </article>

            <article class="card">

                <h2>▣ Códigos QR</h2>

                <h3>
                    <?php echo $totalQR; ?>
                </h3>

                <a href="qr/index.php">
                    Entrar
                </a>

            </article>

            <article class="card">

                <h2>🧾 Facturas</h2>

                <h3>
                    <?php echo $totalFacturas; ?>
                </h3>

                <a href="facturas/index.php">
                    Entrar
                </a>

            </article>

        </section>

    </main>

</body>

</html>