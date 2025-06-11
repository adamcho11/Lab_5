<?php session_start();
require_once '../../backend/conexion_bd.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contraseña = $_POST['password'];
$rol = $_POST['rol'];
$estado = $_POST['estado'];

// Preparar la consulta
$stmt = $con->prepare('INSERT INTO usuarios(nombre, email, contraseña, rol, estado_cuenta) VALUES(?, ?, ?, ?, ?)');

// Vincular parámetros (todos los parámetros son string)
$stmt->bind_param("ssss", $nombre, $email, $contraseña, $rol);

// Ejecutar la consulta
if ($stmt->execute()) {
    $nuevo_id = $stmt->insert_id; // Obtener el ID insertado

    // Asignar las variables a la sesión después de que la cuenta haya sido registrada
    $_SESSION['id'] = $nuevo_id;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['email'] = $email;
    $_SESSION['rol'] = $rol;
    $_SESSION['estado_cuenta'] = $estado;

    $respuesta = [
        'success' => true,
        'message' => 'Cuenta Registrada'
    ];
} else {
    $respuesta = [
        'success' => false,
        'message' => 'Error al Crear Cuenta'
    ];
}

echo json_encode($respuesta);
?>
