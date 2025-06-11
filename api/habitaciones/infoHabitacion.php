<?php
require_once '../../backend/conexion_bd.php';

$id_Habit = $_GET['id'];

$sql = "SELECT 
            u.nombre, 
            u.id AS id_user, 
            h.numero, 
            h.id AS id_habit, 
            r.id AS id, 
            r.fecha_ingreso, 
            r.fecha_salida, 
            r.estado 
        FROM reservas r 
        JOIN usuarios u ON r.usuario_id = u.id 
        JOIN habitaciones h ON r.habitacion_id = h.id 
        WHERE h.id = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_Habit);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No Existe Reserva Registrada']);
}
?>