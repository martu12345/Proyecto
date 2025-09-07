<?php
$host = "localhost";       
$db   = "MiProyecto";    
$user = "root";         
$pass = "pepapig";      


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


?>
