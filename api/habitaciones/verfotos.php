<?php
require_once '../../backend/conexion_bd.php';
$sql = "SELECT h.*, t.nombre_tipo, t.superficie, t.numero_camas,
        (SELECT fotografia FROM fotografias_habitacion f WHERE f.habitacion_id = h.id ORDER BY orden ASC LIMIT 1) AS fotografia
        FROM habitaciones h
        JOIN tipo_habitacion t ON h.tipo_id = t.id";
$res = $con->query($sql);
$habitaciones = [];
while ($h = $res->fetch_assoc()) $habitaciones[] = $h;
echo json_encode($habitaciones);
?>