<?php
require_once '../../backend/conexion_bd.php';

$sql = "SELECT h.*, t.nombre_tipo, t.superficie, t.numero_camas,
        (SELECT fotografia FROM fotografias_habitacion f WHERE f.habitacion_id = h.id ORDER BY orden ASC LIMIT 1) AS fotografia
        FROM habitaciones h
        LEFT JOIN tipo_habitacion t ON t.id = h.tipo_id
        LEFT JOIN fotografias_habitacion fh ON fh.habitacion_id = h.id 
        GROUP BY h.id";

$res = $con->query($sql);
$habitaciones = [];

while ($h = $res->fetch_assoc()) {
    $habitaciones[] = $h;
}

echo json_encode($habitaciones);
header('Content-Type: application/json');