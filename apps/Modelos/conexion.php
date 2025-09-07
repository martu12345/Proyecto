<?php
$host = "localhost";       
$db   = "MiProyecto";    
$user = "root";         
$pass = "M4rtina2007.";      


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// prueba para ver si fucona la base de datos
$sql = "INSERT INTO Usuario (email, contraseña) VALUES ('martina@example.com', '123456')";


if ($conn->query($sql) === TRUE) {
    echo "Registro insertado correctamente 😎";
} else {
    echo "Error al insertar: " . $conn->error;
}



?>
