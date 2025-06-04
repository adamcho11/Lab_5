<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo admin']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';
$rol = $data->rol ?? 'cliente'; // El admin decide el rol
if ($nombre && $email && $password && $rol) {
    $hash = sha1($password);
    $stmt = $con->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'mensaje' => 'No se pudo crear']);
} else echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
?>
