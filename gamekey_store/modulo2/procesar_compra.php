<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'gamekey_store') {
    header('Location: ../../index.php'); exit;
}
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$error = '';
$orderId = 0;
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new RuntimeException('Solicitud no válida.');
    $nombre = trim($_POST['nombre_cliente'] ?? '');
    $direccion = trim($_POST['direccion_entrega'] ?? '');
    $metodo = trim($_POST['metodo_pago'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');
    $rawCart = $_POST['carrito'] ?? '';
    $cart = json_decode($rawCart, true);
    if ($nombre === '' || mb_strlen($nombre) > 150) throw new RuntimeException('Escribe un nombre válido.');
    if ($direccion === '' || mb_strlen($direccion) > 255) throw new RuntimeException('Escribe los datos de entrega.');
    if (!in_array($metodo, ['Efectivo','Transferencia','Tarjeta'], true)) throw new RuntimeException('Selecciona un método de pago válido.');
    if (!is_array($cart) || count($cart) < 1 || count($cart) > 30) throw new RuntimeException('El carrito está vacío o no es válido.');

    require __DIR__ . '/../../modulo_3/config/conexion.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn->set_charset('utf8mb4');

    // Catálogo permitido del sitio. Los precios se toman del servidor, no del navegador.
    $catalogo = [
        'marvels spiderman 2' => ['titulo'=>'Marvel Spider-Man 2','precio'=>49.99,'plataforma'=>'PS5'],
        'marvel spider man 2' => ['titulo'=>'Marvel Spider-Man 2','precio'=>49.99,'plataforma'=>'PS5'],
        'elden ring' => ['titulo'=>'Elden Ring','precio'=>39.99,'plataforma'=>'PC'],
        'god of war ragnarok' => ['titulo'=>'God of War Ragnarök','precio'=>49.99,'plataforma'=>'PS5'],
        'god of war ragnarök' => ['titulo'=>'God of War Ragnarök','precio'=>49.99,'plataforma'=>'PS5'],
        'forza horizon 5' => ['titulo'=>'Forza Horizon 5','precio'=>44.99,'plataforma'=>'Xbox'],
        'ea sports fc 26' => ['titulo'=>'EA Sports FC 26','precio'=>59.99,'plataforma'=>'PS5'],
        'resident evil 4' => ['titulo'=>'Resident Evil 4','precio'=>34.99,'plataforma'=>'PC'],
    ];
    $normalizar = static function($s) { $s = mb_strtolower(trim((string)$s), 'UTF-8'); $s = str_replace(['á','é','í','ó','ú','ñ','’',"'",'-',':'], ['a','e','i','o','u','n','','','',''], $s); return preg_replace('/[^a-z0-9 ]/', '', $s); };
    $productos = [];
    foreach ($cart as $item) {
        $nombreProducto = trim((string)($item['nombre'] ?? ''));
        $clave = $normalizar($nombreProducto);
        $encontrado = null;
        foreach ($catalogo as $k=>$v) { if ($normalizar($k) === $clave) { $encontrado = $v; break; } }
        if (!$encontrado) throw new RuntimeException('El producto "' . $nombreProducto . '" no está autorizado en el catálogo.');
        $cantidad = filter_var($item['cantidad'] ?? 0, FILTER_VALIDATE_INT);
        if (!$cantidad || $cantidad < 1 || $cantidad > 20) throw new RuntimeException('Cantidad no válida para ' . $nombreProducto . '.');
        $productos[] = ['titulo'=>$encontrado['titulo'],'precio'=>$encontrado['precio'],'plataforma'=>$encontrado['plataforma'],'cantidad'=>$cantidad];
    }

    $conn->begin_transaction();
    $ids = [];
    $total = 0.0;
    foreach ($productos as $p) {
        // Busca el producto por título normalizado para reutilizar el inventario del módulo 3.
        $res = $conn->query('SELECT id_videojuego, titulo, stock FROM videojuegos');
        $id = null; $stock = null;
        while ($row = $res->fetch_assoc()) {
            if ($normalizar($row['titulo']) === $normalizar($p['titulo'])) { $id = (int)$row['id_videojuego']; $stock = (int)$row['stock']; break; }
        }
        if ($id === null) {
            $stockInicial = 10;
            $stmt = $conn->prepare("INSERT INTO videojuegos (titulo, plataforma, precio, stock, estado) VALUES (?, ?, ?, ?, 'Disponible')");
            $stmt->bind_param('ssdi', $p['titulo'], $p['plataforma'], $p['precio'], $stockInicial);
            $stmt->execute(); $id = (int)$conn->insert_id; $stock = $stockInicial;
        }
        if ($stock < $p['cantidad']) throw new RuntimeException('No hay suficiente inventario para ' . $p['titulo'] . '. Stock disponible: ' . $stock . '.');
        $upd = $conn->prepare("UPDATE videojuegos SET stock = stock - ?, estado = IF(stock > 0, 'Disponible', 'Agotado') WHERE id_videojuego = ?");
        $upd->bind_param('ii', $p['cantidad'], $id); $upd->execute();
        $p['id_videojuego'] = $id;
        $p['subtotal'] = round($p['precio'] * $p['cantidad'], 2);
        $total += $p['subtotal'];
        $ids[] = $p;
    }
    $total = round($total, 2);
    $stmt = $conn->prepare("INSERT INTO pedidos (nombre_cliente, total, metodo_pago, estado, direccion_entrega, observaciones) VALUES (?, ?, ?, 'Pendiente', ?, ?)");
    $stmt->bind_param('sdsss', $nombre, $total, $metodo, $direccion, $observaciones);
    $stmt->execute(); $orderId = (int)$conn->insert_id;
    $det = $conn->prepare('INSERT INTO detalle_pedido (id_pedido, id_videojuego, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)');
    foreach ($ids as $p) { $det->bind_param('iiidd', $orderId, $p['id_videojuego'], $p['cantidad'], $p['precio'], $p['subtotal']); $det->execute(); }
    $conn->commit();
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) { try { $conn->rollback(); } catch (Throwable $ignored) {} }
    $error = $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo registrar el pedido. Verifica que las bases de datos estén importadas correctamente y vuelve a intentarlo.';
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Resultado de compra | GAMEKEY STORE"><link rel="stylesheet" href="../css/estilos.css"><style>body{font-family:Arial,sans-serif;background:#f1f5f9;color:#172033}.resultado{max-width:650px;margin:60px auto;background:#fff;padding:32px;border-radius:16px;box-shadow:0 10px 35px #0001}.ok{color:#166534}.err{color:#991b1b}.resultado a{display:inline-block;margin:10px 10px 0 0;padding:12px 16px;border-radius:8px;background:#e23a32;color:white;text-decoration:none}</style></head><body><main class="resultado">
<?php if ($error === ''): ?><h1 class="ok">¡Pedido registrado correctamente!</h1><p>Tu número de pedido es <strong>#<?= (int)$orderId ?></strong>.</p><p>El pedido ya está guardado en el módulo 3 y puede consultarse desde el panel de gestión.</p><script>try{localStorage.removeItem('carritoGamekey');}catch(e){}</script><a href="index.php">Volver a la tienda</a><a href="../../index.php">Inicio de sesión</a>
<?php else: ?><h1 class="err">No se pudo completar la compra</h1><p><?= h($error) ?></p><p>Tu carrito no se ha borrado; puedes volver e intentarlo otra vez.</p><a href="checkout.php">Volver a intentar</a><a href="carrito.php">Regresar al carrito</a><?php endif; ?>
</main></body></html>
