<?php
session_start();
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'mensaje' => 'No logueado']); exit;
}
$es_admin = $_SESSION['usuario']['rol'] === 'administrador';
$es_propio = $_SESSION['usuario']['id'] == $id;
if (!$es_admin && !$es_propio) {
    echo json_encode(['success' => false, 'mensaje' => 'No permitido']); exit;
}
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';
$rol = $data->rol ?? '';
$estado = $data->estado ?? '';
if ($es_admin) {
    if ($password) {
        $hash = sha1($password);
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=?, rol=?, estado=? WHERE id=?");
        $stmt->bind_param("sssssi", $nombre, $email, $hash, $rol, $estado, $id);
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, rol=?, estado=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $email, $rol, $estado, $id);
    }
} else {
    if ($password) {
        $hash = sha1($password);
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $email, $hash, $id);
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $nombre, $email, $id);
    }
}
if ($stmt->execute()) echo json_encode(['success' => true]);
else echo json_encode(['success' => false, 'mensaje' => 'Error']);
?>
