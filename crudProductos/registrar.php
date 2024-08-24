<?php

require '../config/conexion.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$categoria_id = $_POST['categoria_id'];

// Manejo de la imagen
$direc_imagen = "../imagenes/";
$direc_file = $direc_imagen . basename($_FILES["imagen"]["name"]);
$imageFileType = strtolower(pathinfo($direc_file, PATHINFO_EXTENSION));

// Verificar si la imagen es real 
$check = getimagesize($_FILES["imagen"]["tmp_name"]);
if($check === false){
    die("El archivo no es una imagen.");
}

// Intentar subir la imagen
if(!move_uploaded_file($_FILES["imagen"]["tmp_name"], $direc_file)){
    die("Error al subir la imagen.");
}

// Guardar la ruta de la imagen en la base de datos
$imagen = basename($_FILES["imagen"]["name"]);

// Usar consultas preparadas para evitar inyecciones SQL
$stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, cantidad, categoria_id, imagen) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $cantidad, $categoria_id, $imagen);

if ($stmt->execute()) {
    header("Location: ../productos.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
