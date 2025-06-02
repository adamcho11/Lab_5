<?php
require_once '../db.php';
$sql = "SELECT h.id, h.numero, h.piso, t.nombre_tipo, t.superficie, t.numero_camas
        FROM habitaciones h
        JOIN tipo_habitacion t ON h.tipo_id = t.id";
$res = $con->query($sql);
$habitaciones = [];
while ($h = $res->fetch_assoc()) {
    $habitaciones[] = $h;
}
echo json_encode($habitaciones);
?>
