<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['modulo_acceso'] ?? '') !== 'modulo1' || ($_SESSION['rol'] ?? '') !== 'administrador') {
    header('Location: ../../index.php'); exit;
}
require_once __DIR__ . '/../../modulo_3/config/conexion.php';
$pagina_actual = basename($_SERVER['PHP_SELF']);
$resultado = $conn->query('SELECT id_pedido, nombre_cliente, fecha_pedido, total, metodo_pago, estado, direccion_entrega FROM pedidos ORDER BY fecha_pedido DESC');
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Pedidos | Panel administrativo</title><link rel="stylesheet" href="../css/dashboard.css"><style>.pedidos-wrap{padding:25px;overflow-x:auto}.pedidos-wrap table{width:100%;border-collapse:collapse;background:white}.pedidos-wrap th,.pedidos-wrap td{padding:12px;border-bottom:1px solid #e2e8f0;text-align:left}.pedidos-wrap th{background:#172033;color:white}.pedido-aviso{margin:0 25px;padding:14px;background:#eff6ff;border-radius:8px}</style></head><body>
<?php include __DIR__ . '/menu.php'; ?>
<main class="contenido pedidos-wrap"><h1>Pedidos de GameKey Store</h1><p class="pedido-aviso">Estos pedidos se registran cuando un cliente confirma su compra en la tienda. La gestión de inventario, QR y facturas se encuentra en el Módulo 3.</p><div style="overflow-x:auto;margin-top:20px"><table><thead><tr><th>N.º</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Pago</th><th>Estado</th><th>Entrega</th></tr></thead><tbody>
<?php if ($resultado && $resultado->num_rows): while($p=$resultado->fetch_assoc()): ?><tr><td>#<?= (int)$p['id_pedido'] ?></td><td><?= htmlspecialchars($p['nombre_cliente'],ENT_QUOTES,'UTF-8') ?></td><td><?= htmlspecialchars(date('d/m/Y H:i',strtotime($p['fecha_pedido'])),ENT_QUOTES,'UTF-8') ?></td><td>$<?= number_format((float)$p['total'],2) ?></td><td><?= htmlspecialchars($p['metodo_pago'],ENT_QUOTES,'UTF-8') ?></td><td><?= htmlspecialchars($p['estado'],ENT_QUOTES,'UTF-8') ?></td><td><?= htmlspecialchars($p['direccion_entrega'] ?? 'Sin dirección',ENT_QUOTES,'UTF-8') ?></td></tr><?php endwhile; else: ?><tr><td colspan="7">Todavía no hay pedidos registrados.</td></tr><?php endif; ?>
</tbody></table></div><p style="margin-top:20px"><a href="dashboard.php">← Volver al panel</a> · <a href="../../cerrar_sesion.php">Cerrar sesión</a></p></main></body></html>
