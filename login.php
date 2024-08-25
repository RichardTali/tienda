<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
        background-color: #ffffff; /* Fondo claro */
    }
    .login-container {
        background-color: #ffffff; /* Fondo blanco para el formulario */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
        padding: 20px;
    }
     /* Botón "Ingresar" - Azul claro */
     .btn-primary-custom {
        background-color: #3b79f6; /* Azul claro */
        border-color: #007bff;
    }
    .btn-primary-custom:hover {
        background-color: #0056b3; /* Azul más oscuro para hover */
        border-color: #004085;
    }
    /* Botón "Registrarse" - Azul oscuro */
    .btn-success-custom {
        background-color: #0055ff; /* Azul medio */
        border-color: #1c73b9;
    }
    .btn-success-custom:hover {
        background-color: #105a8d; /* Azul oscuro para hover */
        border-color: #0a3d61;
    }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <h1 class="text-center mt-4">PrinceLicorera</h1>

    <?php if ($status == 'success'): ?>
        <div class="alert alert-success text-center" role="alert">
            Registro exitoso. Por favor, inicia sesión.
        </div>
    <?php endif; ?>

    <div class="container mt-5 d-flex justify-content-center">
        <div class="col-md-6 col-lg-4 login-container">
            <form action="logueo.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary-custom">Ingresar</button>
                    <a href="form_registro.php" class="btn btn-success-custom">Registrarse</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
