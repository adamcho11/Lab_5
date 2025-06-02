<?php
require_once '../db.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';
$rol = $data->rol ?? '';
if ($id && $nombre && $email && $rol) {
    if ($password) {
        $hash = sha1($password);
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=?, rol=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $email, $hash, $rol, $id);
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $email, $rol, $id);
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar usuario']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
}
?>
