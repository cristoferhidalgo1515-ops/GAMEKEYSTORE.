<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
function exigirModulo(string $modulo): void {
    if (($_SESSION['modulo_acceso'] ?? '') !== $modulo) {
        header('Location: ' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/../../index.php');
        exit;
    }
}
