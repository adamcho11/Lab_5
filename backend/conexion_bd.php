<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'hotel';

$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
else {
    //echo "conexion exitosa";
}
?>
