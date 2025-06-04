<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo admin']); exit;
}
require_once '../../backend/conexion_bd.php';
$res = $con->query("SELECT id, nombre, email, rol, estado FROM usuarios");
$usuarios = [];
while ($u = $res->fetch_assoc()) $usuarios[] = $u;
echo json_encode($usuarios);
?>
