<?php
require_once '../../backend/conexion_bd.php';

$id = $_POST['id'];
$habitacion_id = $_POST['habitacion'];
$orden = $_POST['orden'];
$fotografia = "";

// Si se subió una nueva imagen, se guarda; si no, se conserva la existente
if ($_FILES['fotografia']["name"] != "") {
    $datosfotografia = explode('.', $_FILES['fotografia']['name']);
    $fotografia = uniqid() . '.' . $datosfotografia[1];
    copy($_FILES['fotografia']['tmp_name'], "../../frontend/Pagina Admin/imgHabit/" . $fotografia);

    // Actualizar también la imagen
    $stmt = $con->prepare("UPDATE fotografias_habitacion SET habitacion_id = ?, fotografia = ?, orden = ? WHERE id = ?");
    $stmt->bind_param("ssii", $habitacion_id, $fotografia, $orden, $id);
} else {
    // Sin actualizar la imagen
    $stmt = $con->prepare("UPDATE fotografias_habitacion SET habitacion_id = ?, orden = ? WHERE id = ?");
    $stmt->bind_param("iii", $habitacion_id, $orden, $id);
}

if ($stmt->execute()) {
    echo json_encode(['message' => 'Fotografía actualizada correctamente']);
} else {
    echo json_encode(['message' => 'Error al actualizar fotografía']);
}
?>
