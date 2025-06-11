<?php session_start();
//include('../db/conexion.php');

if(isset($_SESSION['email'])){
    $nombre = $_SESSION['nombre'];
    $estado = $_SESSION['estado_cuenta'];
    $responder = [
        'success' => true,
        'message' => 'El usuario ya Inicio Sesion',
        'nombre' => $nombre,
        'estado' => $estado
    ];
}else{
    $responder = [
        'success' => false,
        'message' => 'Por favor Inicie Sesion'
    ];
}
echo json_encode($responder);

?>