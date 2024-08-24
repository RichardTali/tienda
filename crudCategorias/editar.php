<?php
require '../config/conexion.php';

// Obtener el ID del registro a editar
$id = $_GET['id'];

// Verificar si se recibió el ID
if (isset($id)) {
    // Obtener los datos actuales del registro
    $sql = "SELECT * FROM categorias WHERE id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "Registro no encontrado.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    

    // Actualizar el registro en la base de datos
    $sql = "UPDATE categorias SET nombre = ? WHERE id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        // Aquí usamos "ssii" porque hay 3 strings y 1 integer
        $stmt->bind_param("si", $nombre,$id);
        if ($stmt->execute()) {
            header("Location: ../categoria.php");
        } else {
            echo "Error al actualizar el registro.";
        }
        $stmt->close();
    } else {
        echo "Error en la consulta.";
    }
    $conexion->close();
}










































