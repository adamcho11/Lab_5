<?php
session_start();

require_once '../../backend/conexion_bd.php';

$id = $_POST['habitacion'];
$orden = $_POST['orden'];

$fotografia = "";

if ($_FILES['fotografia']["name"] != "") {
    $datosfotografia = explode('.', $_FILES['fotografia']['name']);
    $fotografia = uniqid() . '.' . $datosfotografia[1];
    copy($_FILES['fotografia']['tmp_name'], "../../frontend/imgHabit/" . $fotografia);
}

$stmt = $con->prepare('INSERT INTO fotografias_habitacion(habitacion_id, fotografia, orden) VALUES(?, ?, ?)');
$stmt->bind_param("isi", $id, $fotografia, $orden);

if ($stmt->execute()) {
    $responder = [
        'message' => 'Fotografia de la habitacion creada'
    ];
} else {
    $responder = [
        'message' => 'Error al crear la fotografia de la habitacion'
    ];
}

echo json_encode($responder);
?>