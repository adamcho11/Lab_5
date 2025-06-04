<?php
require_once '../../backend/conexion_bd.php';
$sql = "SELECT h.*, t.nombre_tipo, t.superficie, t.numero_camas
        FROM habitaciones h
        JOIN tipo_habitacion t ON h.tipo_id = t.id";
$res = $con->query($sql);
$habitaciones = [];
while ($h = $res->fetch_assoc()) $habitaciones[] = $h;
echo json_encode($habitaciones);
?>
