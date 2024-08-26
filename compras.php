<?php

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}

require 'config/conexion.php';



$sql = "select id, nombre_usuario, fecha, subtotal, total
        from compras;";


$result = $conexion->query($sql);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Para utilizar Google Fonts para los iconos -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h1>Listado de Compras</h1>
        <br>
        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">Registrar</a>

        <br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre Usuario</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Total</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <th scope='row'>" . htmlspecialchars($row["id"]) . "</th>
                    <td>" . htmlspecialchars($row["nombre_usuario"]) . "</td>
                    <td>" . htmlspecialchars($row["fecha"]) . "</td>
                    <td>" . htmlspecialchars($row["subtotal"]) . "</td>
                    <td>" . htmlspecialchars($row["total"]) . "</td>
                    
                    <td>
                        
                        <a href='crudCompras/eliminar.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
                            <span class='material-icons'>delete</span>
                        </a>
                    </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay datos</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>