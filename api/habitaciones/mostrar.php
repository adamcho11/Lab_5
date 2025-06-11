<?php
require_once '../../backend/conexion_bd.php';

$sql = "SELECT 
            MIN(fh.id) AS foto_id, 
            fh.fotografia, 
            h.id, 
            h.numero, 
            h.piso, 
            h.precio, 
            t.nombre_tipo, 
            t.superficie, 
            t.numero_camas
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
?>
