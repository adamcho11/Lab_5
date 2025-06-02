<?php
require_once '../db.php';
$data = json_decode(file_get_contents("php://input"));
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';
$rol = $data->rol ?? 'cliente';
if ($nombre && $email && $password) {
    $hash = sha1($password);
    $stmt = $con->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al crear usuario']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
}
?>
