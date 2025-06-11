<?php session_start();
require_once '../../backend/conexion_bd.php';

$nombreH = $_POST['nombreH'];
$piso = $_POST['piso'];
$id_tipo_habitacion = $_POST['TipoH'];
// 3. INSERTAR habitación
$stmt = $con->prepare('INSERT INTO habitaciones(numero, piso, tipo_id) VALUES(?, ?, ?)');
$stmt->bind_param("sii", $nombreH, $piso, $id_tipo_habitacion);

if ($stmt->execute()) {
    $responder = [
        'message' => 'Habitacion creada'
    ];
} else {
    $responder = [
        'message' => 'Error al crear la habitacion'
    ];
};
echo json_encode($responder);
?>