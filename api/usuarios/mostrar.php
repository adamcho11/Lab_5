<?php
session_start();

require_once '../../backend/conexion_bd.php';
$res = $con->query("SELECT * FROM usuarios");
$usuarios = [];
while ($u = $res->fetch_assoc()) $usuarios[] = $u;
echo json_encode($usuarios);
?>
