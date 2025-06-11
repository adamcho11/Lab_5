<?php
session_start();
require_once '../../backend/conexion_bd.php';

$id = $_GET['id'];
$rol = $_GET['rol'];

$stmt = $con->prepare("UPDATE usuarios SET rol=? WHERE id=?");
$stmt->bind_param("si", $rol, $id);


// Ejecutar la consulta
if ($stmt->execute()) {
    $respuesta = [
        'success' => true,
        'message' => 'Se cambio de rol correctamente'
    ];
} else {
    $respuesta = [
        'success' => false,
        'message' => 'Error al cambiar rol'
    ];
}

echo json_encode($respuesta);
?>