<?php
require_once '../../backend/conexion_bd.php';

header('Content-Type: application/json');

// Get userId from cookie
$userId = $_COOKIE['userId'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'mensaje' => 'No autenticado.']);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';

if ($nombre && $email) {
    $stmt = $con->prepare("SELECT id FROM usuarios WHERE email=? AND id!=?");
    $stmt->bind_param("si", $email, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'mensaje' => 'El email ya está registrado por otro usuario.']);
        exit();
    }

    if ($password) {
        $hash = sha1($password);
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $email, $hash, $userId);
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $nombre, $email, $userId);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Datos actualizados.']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar datos.']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos.']);
}

$stmt->close();
$con->close();
?>
