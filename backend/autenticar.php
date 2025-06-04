<?php
session_start();
require_once 'conexion_bd.php';

$data = json_decode(file_get_contents("php://input"));
$email = $data->email ?? '';
$password = $data->password ?? '';

if ($email && $password) {
    $hash = sha1($password); // Usa SHA1 porque así está en tu BD
    $stmt = $con->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE email=? AND contraseña=?");
    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        echo json_encode(['success' => true, 'rol' => $usuario['rol']]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Credenciales incorrectas']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Faltan datos']);
}
?>
