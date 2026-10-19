<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'modulo3') { header('Location: ../../../index.php'); exit; }

require_once "../../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$idPedido = isset($_POST["id_pedido"])
    ? (int) $_POST["id_pedido"]
    : 0;

if ($idPedido <= 0) {
    die("El pedido seleccionado no es válido.");
}

/* Comprobar si ya existe una factura */

$sqlExiste = "
    SELECT id_factura
    FROM facturas
    WHERE id_pedido = ?
";

$stmtExiste = $conn->prepare($sqlExiste);
$stmtExiste->bind_param("i", $idPedido);
$stmtExiste->execute();

$resultadoExiste = $stmtExiste->get_result();

if ($resultadoExiste->num_rows > 0) {
    die("Este pedido ya tiene una factura.");
}

$stmtExiste->close();

/* Consultar el pedido */

$sqlPedido = "
    SELECT total
    FROM pedidos
    WHERE id_pedido = ?
";

$stmtPedido = $conn->prepare($sqlPedido);
$stmtPedido->bind_param("i", $idPedido);
$stmtPedido->execute();

$resultadoPedido = $stmtPedido->get_result();
$pedido = $resultadoPedido->fetch_assoc();

$stmtPedido->close();

if (!$pedido) {
    die("El pedido no existe.");
}

/*
    Para este ejemplo, el total del pedido será el subtotal.
    Luego calculamos el IVA del 13%.
*/

$subtotal = (float) $pedido["total"];
$impuesto = round($subtotal * 0.13, 2);
$total = round($subtotal + $impuesto, 2);

/* Número temporal único */

$numeroTemporal = "TEMP-" . uniqid();

$sqlInsertar = "
    INSERT INTO facturas
    (
        id_pedido,
        numero_factura,
        subtotal,
        impuesto,
        total
    )
    VALUES (?, ?, ?, ?, ?)
";

$stmtInsertar = $conn->prepare($sqlInsertar);

if (!$stmtInsertar) {
    die("Error al preparar la factura: " . $conn->error);
}

$stmtInsertar->bind_param(
    "isddd",
    $idPedido,
    $numeroTemporal,
    $subtotal,
    $impuesto,
    $total
);

if (!$stmtInsertar->execute()) {
    die("Error al guardar la factura: " . $stmtInsertar->error);
}

$idFactura = $stmtInsertar->insert_id;
$stmtInsertar->close();

/* Crear número final: FAC-000001 */

$numeroFactura = "FAC-" . str_pad(
    (string) $idFactura,
    6,
    "0",
    STR_PAD_LEFT
);

$sqlActualizar = "
    UPDATE facturas
    SET numero_factura = ?
    WHERE id_factura = ?
";

$stmtActualizar = $conn->prepare($sqlActualizar);
$stmtActualizar->bind_param(
    "si",
    $numeroFactura,
    $idFactura
);
$stmtActualizar->execute();
$stmtActualizar->close();

header(
    "Location: index.php?generada=1"
);
exit;