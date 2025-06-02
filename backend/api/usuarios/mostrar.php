<?php
require_once '../db.php';
$res = $con->query("SELECT id, nombre, email, rol FROM usuarios");
$usuarios = [];
while ($u = $res->fetch_assoc()) {
    $usuarios[] = $u;
}
echo json_encode($usuarios);
?>
