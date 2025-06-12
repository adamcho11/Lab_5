<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "hotel";

$con = new mysqli($host, $user, $password, $database);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
?>
