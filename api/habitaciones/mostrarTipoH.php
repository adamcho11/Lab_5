<?php
require_once '../../backend/conexion_bd.php';

$sql = "SELECT * FROM tipo_habitacion";
$res = $con->query($sql);
$habitaciones = [];
while ($h = $res->fetch_assoc()) $habitaciones[] = $h;
echo json_encode($habitaciones);
?>