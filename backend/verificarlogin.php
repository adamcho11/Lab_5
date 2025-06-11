<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('conexion_bd.php');

header('Content-Type: application/json');

$responder = [
    'success' => false,
    'message' => 'Por favor Inicie Sesion'
];

if(isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
    
    $stmt = $con->prepare("SELECT nombre, estado, rol FROM usuarios WHERE id = ?");

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $con->error]);
        exit();
    }

    $stmt->bind_param("i", $userId);

    if ($stmt->execute() === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        $responder = [
            'success' => true,
            'message' => 'El usuario ya Inicio Sesion',
            'nombre' => $usuario['nombre'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['rol']
        ];
    }
}
echo json_encode($responder);

?>