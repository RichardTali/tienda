<?php
require 'config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['contraseña'], PASSWORD_BCRYPT); // Asegúrate de hashear la contraseña
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $fecha_registro = date('Y-m-d H:i:s');
    $rol = 'cliente'; // Establecer el rol por defecto como 'cliente'

    $sql = "INSERT INTO usuarios (nombre, email, password, direccion, telefono, fecha_registro, rol) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("sssssss", $nombre, $email, $password, $direccion, $telefono, $fecha_registro, $rol);
        if ($stmt->execute()) {
            // Redirigir con un mensaje de éxito a form_registro.php
            header("Location: login.php?status=success");
            exit(); // Asegúrate de detener la ejecución después de la redirección
        } else {
            echo "Error al registrar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
    $conexion->close();
}
?>
