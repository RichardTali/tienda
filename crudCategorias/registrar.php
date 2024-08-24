<?php

require '../config/conexion.php';

$nombre = $_POST['nombre'];

$stmt = $conexion->prepare("insert into categorias (nombre) VALUES (?)");

// Verificar si la preparación fue exitosa
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $nombre);


if ($stmt->execute()) {
    header("Location: ../categoria.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();







