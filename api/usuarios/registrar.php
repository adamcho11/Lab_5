<?php session_start();
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$nombre = $data->nombre ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';
$rol = 'cliente'; // Fijo para registros públicos
if ($nombre && $email && $password) {
    $hash = sha1($password);
    $stmt = $con->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);
    if ($stmt->execute()) {
        // Set HttpOnly cookie for session management
        setcookie("userId", $con->insert_id, [
            'expires' => time() + (86400 * 30), // 30 days
            'path' => '/',
            'httponly' => true,
            // 'secure' => true, // Uncomment in production with HTTPS
            'samesite' => 'Lax'
        ]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'No se pudo registrar']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
}
?>
