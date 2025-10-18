<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

// Datos de prueba
$email = "pruebapropietario2@hotmail.com";
$contrasena = "Peppapig1*";
$telefono = "099123456";

// Hasheamos la contraseña con bcrypt
$hash = password_hash($contrasena, PASSWORD_BCRYPT);

// Insertamos en la tabla usuario
$sqlUsuario = "INSERT INTO usuario (Email, contraseña) VALUES (?, ?)";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("ss", $email, $hash);

if ($stmtUsuario->execute()) {
    $idUsuario = $conn->insert_id;

    // Insertamos en la tabla propietario
    $sqlProp = "INSERT INTO propietario (IdUsuario) VALUES (?)";
    $stmtProp = $conn->prepare($sqlProp);
    $stmtProp->bind_param("i", $idUsuario);
    $stmtProp->execute();

    // Insertamos el teléfono
    $sqlTel = "INSERT INTO telefono (IdUsuario, telefono) VALUES (?, ?)";
    $stmtTel = $conn->prepare($sqlTel);
    $stmtTel->bind_param("is", $idUsuario, $telefono);
    $stmtTel->execute();

    echo "✅ Propietario creado correctamente.";
} else {
    echo "❌ Error al crear propietario: " . $stmtUsuario->error;
}
?>
