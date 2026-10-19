<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'modulo3') { header('Location: ../../../index.php'); exit; }

require_once "../../config/conexion.php";

$sql = "
    SELECT
        p.id_pedido,
        p.nombre_cliente,
        p.total,
        p.estado
    FROM pedidos AS p
    LEFT JOIN facturas AS f
        ON p.id_pedido = f.id_pedido
    WHERE f.id_factura IS NULL
    ORDER BY p.id_pedido DESC
";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("Error al consultar los pedidos: " . $conn->error);
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

    <title>Generar factura | Gamekey Store</title>

    <link
        rel="stylesheet"
        href="../../css/estilos.css?v=7"
    >
</head>

<body>

    <header class="encabezado">
        <h1>🎮 GAMEKEY STORE</h1>
        <p>Generar factura</p>
    </header>

    <main class="contenedor">

        <div class="formulario-contenedor">

            <h2>Seleccionar pedido</h2>

            <p class="texto-secundario">
                Cada pedido puede tener una sola factura.
            </p>

            <form
                action="guardar_factura.php"
                method="POST"
            >

                <div class="grupo-formulario">

                    <label for="id_pedido">
                        Pedido
                    </label>

                    <select
                        id="id_pedido"
                        name="id_pedido"
                        required
                    >

                        <option value="">
                            Selecciona un pedido
                        </option>

                        <?php while ($pedido = $resultado->fetch_assoc()): ?>

                            <option
                                value="<?php echo $pedido["id_pedido"]; ?>"
                            >
                                Pedido #<?php echo $pedido["id_pedido"]; ?>
                                -
                                <?php
                                echo htmlspecialchars(
                                    $pedido["nombre_cliente"]
                                );
                                ?>
                                -
                                $<?php
                                echo number_format(
                                    $pedido["total"],
                                    2
                                );
                                ?>
                                -
                                <?php
                                echo htmlspecialchars(
                                    $pedido["estado"]
                                );
                                ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="acciones-formulario">

                    <button
                        type="submit"
                        class="boton-guardar"
                    >
                        Generar factura
                    </button>

                    <a
                        href="index.php"
                        class="boton-cancelar"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </main>

</body>

</html>