<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión']); exit;
}
require_once '../../backend/conexion_bd.php';
$es_admin = $_SESSION['usuario']['rol'] === 'administrador';
if ($es_admin) {
    $res = $con->query("SELECT r.*, u.nombre as usuario, h.numero as habitacion FROM reservas r JOIN usuarios u ON r.usuario_id=u.id JOIN habitaciones h ON r.habitacion_id=h.id");
} else {
    $usuario_id = $_SESSION['usuario']['id'];
    $res = $con->query("SELECT r.*, h.numero as habitacion FROM reservas r JOIN habitaciones h ON r.habitacion_id=h.id WHERE r.usuario_id=$usuario_id");
}
$reservas = [];
while ($r = $res->fetch_assoc()) $reservas[] = $r;
echo json_encode($reservas);
?>
