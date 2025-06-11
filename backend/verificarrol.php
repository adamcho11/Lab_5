<?php session_start();

$rol = $_SESSION['rol'];

if($rol == 'administrador'){
    $respuesta = [
        'success' => true,
        'message' => 'Bienvenido Administrado',
    ];
}else{
    $respuesta = [
        'success' => false,
        'message' => 'Bienvenido Cliente'
    ];
}

echo json_encode($respuesta);

?>