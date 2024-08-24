<?php
require '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $categoria_id = $_POST['categoria_id'];
    $imagen = $_FILES['imagen']['name'];
    
    // Si se subió una nueva imagen, maneja la carga de archivos
    if ($imagen) {
        $target_dir = "../imagenes/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
        $imagen_sql = ", imagen = ?";
        $imagen_param = $imagen;
    } else {
        $imagen_sql = "";
        $imagen_param = "";
    }

    // Actualizar el registro en la base de datos
    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, categoria_id = ? $imagen_sql WHERE id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        if ($imagen) {
            $stmt->bind_param("ssdiisi", $nombre, $descripcion, $precio, $cantidad, $categoria_id, $imagen_param, $id);
        } else {
            $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $cantidad, $categoria_id, $id);
        }
        if ($stmt->execute()) {
            header("Location: ../productos.php");
        } else {
            echo "Error al actualizar el registro.";
        }
        $stmt->close();
    } else {
        echo "Error en la consulta.";
    }
    $conexion->close();
}
?>
