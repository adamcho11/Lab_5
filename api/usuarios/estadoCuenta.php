<?php
session_start();
require_once '../../backend/conexion_bd.php';

$id = $_GET['id'];
$estado = $_GET['estado'];

$stmt = $con->prepare("UPDATE usuarios SET estado=? WHERE id=?");
$stmt->bind_param("si", $estado, $id);

$mensaje = "Cuenta: ".$estado;

// Ejecutar la consulta
if ($stmt->execute()) {
    $respuesta = [
        'success' => true,
        'message' => $mensaje
    ];
} else {
    $respuesta = [
        'success' => false,
        'message' => 'Error al modificar la cuenta'
    ];
}

echo json_encode($respuesta);
?>