<?php
session_start();
include 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conexion, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // Guardar información en la sesión
            $_SESSION['id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir dependiendo del rol
            if ($user['rol'] === 'admin') {
                header("Location: productos.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "No existe un usuario registrado con ese correo";
    }
}
?>
