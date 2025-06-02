<?php
require_once '../db.php';
$sql = "SELECT r.id, r.fecha_ingreso, r.fecha_salida, r.estado,
               u.nombre AS usuario, h.numero AS habitacion
        FROM reservas r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN habitaciones h ON r.habitacion_id = h.id";
$res = $con->query($sql);
$reservas = [];
while ($r = $res->fetch_assoc()) {
    $reservas[] = $r;
}
echo json_encode($reservas);
?>
