<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'modulo3') { header('Location: ../../../index.php'); exit; }


require_once "../../config/conexion.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID de factura no válido.");
}

$idFactura = (int) $_GET["id"];

/* Consultar factura y pedido */

$sqlFactura = "
    SELECT
        f.id_factura,
        f.numero_factura,
        f.fecha_emision,
        f.subtotal,
        f.impuesto,
        f.total,
        p.id_pedido,
        p.nombre_cliente,
        p.metodo_pago,
        p.direccion_entrega,
        p.estado AS estado_pedido
    FROM facturas AS f
    INNER JOIN pedidos AS p
        ON f.id_pedido = p.id_pedido
    WHERE f.id_factura = ?
";

$stmtFactura = $conn->prepare($sqlFactura);

if (!$stmtFactura) {
    die("Error al preparar la factura: " . $conn->error);
}

$stmtFactura->bind_param("i", $idFactura);
$stmtFactura->execute();

$resultadoFactura = $stmtFactura->get_result();
$factura = $resultadoFactura->fetch_assoc();

$stmtFactura->close();

if (!$factura) {
    die("La factura no existe.");
}

/* Consultar productos */

$sqlDetalles = "
    SELECT
        v.titulo,
        v.plataforma,
        dp.cantidad,
        dp.precio_unitario,
        dp.subtotal
    FROM detalle_pedido AS dp
    INNER JOIN videojuegos AS v
        ON dp.id_videojuego = v.id_videojuego
    WHERE dp.id_pedido = ?
    ORDER BY dp.id_detalle ASC
";

$stmtDetalles = $conn->prepare($sqlDetalles);

if (!$stmtDetalles) {
    die("Error al consultar los productos: " . $conn->error);
}

$stmtDetalles->bind_param("i", $factura["id_pedido"]);
$stmtDetalles->execute();

$resultadoDetalles = $stmtDetalles->get_result();

/* Preparar filas de productos */

$filasProductos = "";

while ($detalle = $resultadoDetalles->fetch_assoc()) {

    $titulo = htmlspecialchars(
        $detalle["titulo"],
        ENT_QUOTES,
        "UTF-8"
    );

    $plataforma = htmlspecialchars(
        $detalle["plataforma"],
        ENT_QUOTES,
        "UTF-8"
    );

    $cantidad = (int) $detalle["cantidad"];

    $precio = number_format(
        (float) $detalle["precio_unitario"],
        2
    );

    $subtotalDetalle = number_format(
        (float) $detalle["subtotal"],
        2
    );

    $filasProductos .= "
        <tr>
            <td>{$titulo}</td>
            <td>{$plataforma}</td>
            <td class='centrado'>{$cantidad}</td>
            <td class='derecha'>\${$precio}</td>
            <td class='derecha'>\${$subtotalDetalle}</td>
        </tr>
    ";
}

$stmtDetalles->close();

if ($filasProductos === "") {
    $filasProductos = "
        <tr>
            <td colspan='5' class='centrado'>
                No existen productos registrados.
            </td>
        </tr>
    ";
}

/* Limpiar datos antes de colocarlos en el HTML */

$numeroFactura = htmlspecialchars(
    $factura["numero_factura"],
    ENT_QUOTES,
    "UTF-8"
);

$nombreCliente = htmlspecialchars(
    $factura["nombre_cliente"],
    ENT_QUOTES,
    "UTF-8"
);

$direccion = htmlspecialchars(
    $factura["direccion_entrega"] ?: "No registrada",
    ENT_QUOTES,
    "UTF-8"
);

$metodoPago = htmlspecialchars(
    $factura["metodo_pago"],
    ENT_QUOTES,
    "UTF-8"
);

$estadoPedido = htmlspecialchars(
    $factura["estado_pedido"],
    ENT_QUOTES,
    "UTF-8"
);

$fecha = date(
    "d/m/Y H:i",
    strtotime($factura["fecha_emision"])
);

$subtotal = number_format(
    (float) $factura["subtotal"],
    2
);

$impuesto = number_format(
    (float) $factura["impuesto"],
    2
);

$total = number_format(
    (float) $factura["total"],
    2
);

$idPedido = (int) $factura["id_pedido"];

/* HTML que Dompdf convertirá en PDF */

$html = "
<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>

    <style>
        @page {
            margin: 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222222;
        }

        .cabecera {
            width: 100%;
            margin-bottom: 20px;
        }

        .cabecera td {
            vertical-align: top;
        }

        .empresa {
            color: #9c007d;
            font-size: 24px;
            font-weight: bold;
        }

        .factura-titulo {
            color: #333333;
            font-size: 26px;
            font-weight: bold;
            text-align: right;
        }

        .factura-numero {
            margin-top: 6px;
            text-align: right;
        }

        .linea {
            height: 2px;
            margin: 12px 0 20px;
            background: #9c007d;
        }

        .informacion {
            width: 100%;
            margin-bottom: 22px;
        }

        .informacion td {
            width: 50%;
            padding: 4px 0;
            vertical-align: top;
        }

        .productos {
            width: 100%;
            border-collapse: collapse;
        }

        .productos th {
            padding: 9px;
            color: #ffffff;
            background: #292940;
            border: 1px solid #dddddd;
        }

        .productos td {
            padding: 9px;
            border: 1px solid #dddddd;
        }

        .centrado {
            text-align: center;
        }

        .derecha {
            text-align: right;
        }

        .totales {
            width: 42%;
            margin-top: 20px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totales td {
            padding: 7px;
            border-bottom: 1px solid #dddddd;
        }

        .total-final td {
            color: #9c007d;
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #9c007d;
        }

        .pie {
            margin-top: 45px;
            padding-top: 12px;
            color: #666666;
            text-align: center;
            border-top: 1px solid #dddddd;
        }
    </style>
</head>

<body>

    <table class='cabecera'>
        <tr>
            <td>
                <div class='empresa'>GAMEKEY STORE</div>
                <div>Tienda de videojuegos</div>
                <div>El Salvador</div>
            </td>

            <td>
                <div class='factura-titulo'>FACTURA</div>
                <div class='factura-numero'>{$numeroFactura}</div>
            </td>
        </tr>
    </table>

    <div class='linea'></div>

    <table class='informacion'>
        <tr>
            <td>
                <strong>Cliente:</strong>
                {$nombreCliente}
            </td>

            <td>
                <strong>Pedido:</strong>
                #{$idPedido}
            </td>
        </tr>

        <tr>
            <td>
                <strong>Dirección:</strong>
                {$direccion}
            </td>

            <td>
                <strong>Fecha:</strong>
                {$fecha}
            </td>
        </tr>

        <tr>
            <td>
                <strong>Método de pago:</strong>
                {$metodoPago}
            </td>

            <td>
                <strong>Estado:</strong>
                {$estadoPedido}
            </td>
        </tr>
    </table>

    <table class='productos'>

        <thead>
            <tr>
                <th>Videojuego</th>
                <th>Plataforma</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            {$filasProductos}
        </tbody>

    </table>

    <table class='totales'>

        <tr>
            <td>Subtotal:</td>
            <td class='derecha'>\${$subtotal}</td>
        </tr>

        <tr>
            <td>IVA:</td>
            <td class='derecha'>\${$impuesto}</td>
        </tr>

        <tr class='total-final'>
            <td>Total:</td>
            <td class='derecha'>\${$total}</td>
        </tr>

    </table>

    <div class='pie'>
        Gracias por comprar en Gamekey Store.
    </div>

</body>
</html>
";

/* Configurar y generar el PDF */

$options = new Options();
$options->set("isHtml5ParserEnabled", true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html, "UTF-8");
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

/*
    Attachment en true hace que el navegador descargue
    directamente el archivo.
*/

$dompdf->stream(
    $numeroFactura . ".pdf",
    ["Attachment" => true]
);

exit;