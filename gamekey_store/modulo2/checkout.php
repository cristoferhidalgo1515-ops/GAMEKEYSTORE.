<?php
session_start();
if (($_SESSION['modulo_acceso'] ?? '') !== 'gamekey_store') {
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finalizar compra | GAMEKEY STORE</title>
<link rel="stylesheet" href="../css/estilos.css">
<style>
.checkout{max-width:760px;margin:40px auto;padding:28px;background:#fff;border-radius:16px;box-shadow:0 10px 35px #10182718;color:#172033}.checkout h1{margin-top:0}.checkout-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.checkout label{display:block;font-weight:700;margin:12px 0 6px}.checkout input,.checkout select,.checkout textarea{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:8px;font:inherit}.checkout textarea{min-height:90px;resize:vertical}.checkout button{margin-top:22px;padding:14px 20px;border:0;border-radius:8px;background:#e23a32;color:white;font-weight:700;cursor:pointer;width:100%}.checkout .resumen{background:#f1f5f9;padding:14px;border-radius:8px;margin-top:18px}.aviso{font-size:13px;color:#64748b}.error{color:#991b1b;background:#fee2e2;padding:12px;border-radius:8px;display:none}@media(max-width:600px){.checkout-grid{grid-template-columns:1fr}.checkout{margin:18px;padding:18px}}
</style>
</head>
<body>
<main class="checkout">
<h1>Finalizar compra</h1>
<p>Completa tus datos para registrar el pedido. El pedido aparecerá en el módulo de gestión.</p>
<div id="error" class="error"></div>
<form id="formCompra" method="post" action="procesar_compra.php">
<input type="hidden" name="carrito" id="carrito">
<div class="checkout-grid">
<div><label for="nombre_cliente">Nombre completo</label><input id="nombre_cliente" name="nombre_cliente" maxlength="150" required></div>
<div><label for="metodo_pago">Método de pago</label><select id="metodo_pago" name="metodo_pago" required><option value="">Selecciona uno</option><option value="Efectivo">Efectivo</option><option value="Transferencia">Transferencia</option><option value="Tarjeta">Tarjeta</option></select></div>
</div>
<label for="direccion_entrega">Dirección / datos de entrega</label><input id="direccion_entrega" name="direccion_entrega" maxlength="255" required>
<label for="observaciones">Observaciones (opcional)</label><textarea id="observaciones" name="observaciones" maxlength="1000"></textarea>
<div class="resumen"><strong>Resumen del carrito</strong><div id="resumenCarrito">Cargando...</div></div>
<p class="aviso">El total se calcula y valida en el servidor. No cierres esta página mientras se registra el pedido.</p>
<button type="submit">Confirmar pedido</button>
</form>
<p><a href="carrito.php">← Volver al carrito</a></p>
</main>
<script>
(function(){
 const key='carritoGamekey'; let items=[];
 try { items=JSON.parse(localStorage.getItem(key)||'[]'); if(!Array.isArray(items)) items=[]; } catch(e){items=[];}
 const error=document.getElementById('error');
 if(!items.length){error.style.display='block';error.textContent='Tu carrito está vacío. Regresa al catálogo y agrega videojuegos antes de continuar.';document.querySelector('button[type=submit]').disabled=true;document.getElementById('resumenCarrito').textContent='Sin productos';}
 else {
  document.getElementById('carrito').value=JSON.stringify(items.map(p=>({nombre:String(p.nombre||''),cantidad:Number(p.cantidad||1)})));
  const total=items.reduce((s,p)=>s+(Number(p.precio)||0)*(Number(p.cantidad)||1),0);
  document.getElementById('resumenCarrito').textContent=items.map(p=>`${p.nombre} × ${p.cantidad||1}`).join(' · ')+` — Total estimado: $${total.toFixed(2)}`;
 }
 document.getElementById('formCompra').addEventListener('submit',function(e){if(!items.length){e.preventDefault();return;} document.querySelector('button[type=submit]').disabled=true;document.querySelector('button[type=submit]').textContent='Registrando pedido...';});
})();
</script>
</body></html>
