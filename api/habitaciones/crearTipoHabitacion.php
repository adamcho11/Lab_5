<?php session_start();
require_once '../../backend/conexion_bd.php';

$nombreTipo = $_POST['nombretipo'];
$superficie = $_POST['superficie'];
$numero_camas = $_POST['ncamas'];
// 3. INSERTAR habitación
$stmt = $con->prepare('INSERT INTO tipo_habitacion(nombre_tipo, superficie, numero_camas) VALUES(?, ?, ?)');
$stmt->bind_param("sii", $nombreTipo, $superficie, $numero_camas);

if ($stmt->execute()) {
    $responder = [
        'message' => 'Tipo de Habitacion creada'
    ];
} else {
    $responder = [
        'message' => 'Error al crear un Tipo de habitacion'
    ];
};
echo json_encode($responder);
?>