<?php
require_once '../../backend/conexion_bd.php';
$id = intval($_GET['id'] ?? 0);
$sql = "SELECT h.*, t.nombre_tipo, t.superficie, t.numero_camas
        FROM habitaciones h
        JOIN tipo_habitacion t ON h.tipo_id = t.id
        WHERE h.id = $id";
$res = $con->query($sql);
$habitacion = $res->fetch_assoc();
$fotos = [];
$res2 = $con->query("SELECT fotografia, orden FROM fotografias_habitacion WHERE habitacion_id = $id ORDER BY orden ASC");
while ($f = $res2->fetch_assoc()) $fotos[] = $f;
echo json_encode(['habitacion' => $habitacion, 'fotos' => $fotos]);
?>
