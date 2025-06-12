<?php session_start();
include('conexion_bd.php');


$email = $_POST['email'];
$password = $_POST['password'];

    if ($usuario) {
        // Set HttpOnly cookie for session management
        setcookie("userId", $usuario['id'], [
            'expires' => time() + (86400 * 30), // 30 days
            'path' => '/',
            'httponly' => true,
            // 'secure' => true, // Uncomment in production with HTTPS
            'samesite' => 'Lax'
        ]);
        $_SESSION['usuario'] = $usuario;
        echo json_encode(['success' => true, 'rol' => $usuario['rol'], 'nombre' => $usuario['nombre']]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Credenciales incorrectas']);
    }
} else {
    $respuesta = [
        'success' => false,
        'message' => 'Usuario o contraseña incorrectos'
    ];
}
echo json_encode($respuesta);
?>
