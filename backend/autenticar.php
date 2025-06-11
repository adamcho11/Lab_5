<?php session_start();
include('conexion_bd.php');


$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para buscar usuario con email y password
$sql = "SELECT * FROM usuarios WHERE email = ? AND contraseña = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['rol'] = $user['rol'];
    $_SESSION['estado_cuenta'] = $user['estado_cuenta'];

    $respuesta = [
        'success' => true,
        'message' => 'Usuario autenticado correctamente',
        'nombre' => $user['nombre'],
        'estado_cuenta' => $user['estado_cuenta']
    ];
} else {
    $respuesta = [
        'success' => false,
        'message' => 'Usuario o contraseña incorrectos'
    ];
}
echo json_encode($respuesta);
?>
