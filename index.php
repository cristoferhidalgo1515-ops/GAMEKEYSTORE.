<?php
session_start();

if (isset($_SESSION['modulo_acceso'])) {
    $destinos = [
        'modulo1' => 'Modulo_1/admin/dashboard.php',
        'gamekey_store' => 'gamekey_store/modulo2/index.php',
        'modulo3' => 'modulo_3/modulo3/dashboard.php',
    ];
    if (isset($destinos[$_SESSION['modulo_acceso']])) {
        header('Location: ' . $destinos[$_SESSION['modulo_acceso']]);
        exit;
    }
}

$usuarios = require __DIR__ . '/config/usuarios.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = strtolower(trim($_POST['correo'] ?? ''));
    $clave = $_POST['contrasena'] ?? '';
    $usuario = $usuarios[$correo] ?? null;
    if ($usuario && password_verify($clave, $usuario['hash'])) {
        session_regenerate_id(true);
        $_SESSION['modulo_acceso'] = $usuario['modulo'];
        $_SESSION['correo'] = $correo;
        $_SESSION['nombre'] = $usuario['nombre'];
        // Compatibilidad con las comprobaciones de sesión del módulo 1.
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['rol'] = ($usuario['modulo'] === 'modulo1') ? 'administrador' : (($usuario['modulo'] === 'gamekey_store') ? 'cliente' : 'modulo3');
        $_SESSION['nombres'] = $usuario['nombre'];
        $destinos = [
            'modulo1' => 'Modulo_1/admin/dashboard.php',
            'gamekey_store' => 'gamekey_store/modulo2/index.php',
            'modulo3' => 'modulo_3/modulo3/dashboard.php',
        ];
        header('Location: ' . $destinos[$usuario['modulo']]);
        exit;
    }
    $error = 'Correo o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso | GameKey Store</title>
<style>
*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:20px;background:linear-gradient(135deg,#101827,#172b45);font-family:Arial,sans-serif;color:#f8fafc}.card{width:min(100%,430px);background:#fff;color:#172033;border-radius:18px;padding:34px;box-shadow:0 22px 65px #0006}.logo{text-align:center;margin-bottom:25px}.logo .icon{font-size:42px}.logo h1{margin:8px 0 4px;font-size:27px;letter-spacing:2px}.logo p{margin:0;color:#64748b}.campo{margin:18px 0}.campo label{display:block;margin-bottom:7px;font-weight:700;font-size:14px}.campo input{width:100%;padding:13px;border:1px solid #cbd5e1;border-radius:9px;font-size:16px}.campo input:focus{outline:2px solid #3b82f6;border-color:#3b82f6}.boton{width:100%;border:0;border-radius:9px;padding:14px;background:#e23a32;color:white;font-size:16px;font-weight:700;cursor:pointer}.boton:hover{background:#c92e27}.error{background:#fee2e2;color:#991b1b;border-radius:8px;padding:11px;font-size:14px}.nota{text-align:center;color:#64748b;font-size:12px;margin-top:22px}
</style></head><body><main class="card"><div class="logo"><div class="icon">🎮</div><h1>GAMEKEY STORE</h1><p>Un solo acceso para los tres módulos</p></div>
<?php if ($error): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post" action="" autocomplete="on"><div class="campo"><label for="correo">Correo electrónico</label><input id="correo" name="correo" type="email" required autocomplete="username" placeholder="tucorreo@ejemplo.com"></div><div class="campo"><label for="contrasena">Contraseña</label><input id="contrasena" name="contrasena" type="password" required autocomplete="current-password" placeholder="Escribe tu contraseña"></div><button class="boton" type="submit">Iniciar sesión</button></form><p class="nota">Acceso centralizado · 3 módulos</p></main></body></html>
