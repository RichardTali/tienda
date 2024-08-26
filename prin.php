<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <br>
        <br>
        <h1>Administrador</h1>

        <?php if (isset($_SESSION['nombre'])): ?>
            <span class="welcome-message">Bienvenido,
                <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?>
                </strong>
            </span>
            <a href="logout.php" class="btn btn-danger btn-sm ms-2">Cerrar Sesión</a>
            <br>
            <br>
            <a href="categoria.php" class="btn btn-primary">Categorias</a>
            <a href="productos.php" class="btn btn-secondary">Productos</a>
            <a href="usuarios.php" class="btn btn-success">Usuarios</a>
            <a href="compras.php" class="btn btn-info">Compras</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>