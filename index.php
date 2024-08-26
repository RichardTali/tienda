<?php
session_start();

// Controlar la caché para asegurar que la página no se muestre en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verificar si el usuario está logueado
$loggedIn = isset($_SESSION['id']);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda en Línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .icons-container {
            position: fixed;
            top: 19px;
            right: 95px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-icon {
            font-size: 24px;
            cursor: pointer;
        }

        .welcome-message {
            font-size: 16px;
            margin-right: 10px;
        }

        .alert {
            display: none;
        }

        .card {
            width: 300px;
            height: 400px;
            margin: 10px;
            display: inline-block;
            vertical-align: top;
            border: none;
            margin-bottom: 50px;
        }

        .card-img-top {
            width: 150px;
            height: 150px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
        }

        .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: calc(100% - 200px);
        }

        .container_footer {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            margin-bottom: 20px;
        }

        .item {
            text-align: center;
            font-size: 1.5em;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="icons-container">
            <?php if ($loggedIn): ?>
                <span class="welcome-message">Bienvenido,
                    <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>!</span>
                <a href="logout.php" class="btn btn-danger btn-sm ms-2">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>

            <div class="cart-icon ms-3">
                <i class="fas fa-shopping-cart" onclick="showCart()"></i>
                <span id="cart-count" class="badge bg-danger">0</span>
            </div>
        </div>

        <h1>Licores</h1>

        <?php
        require 'config/conexion.php';

        $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>
                    <img src='imagenes/" . htmlspecialchars($row['imagen']) . "' class='card-img-top' alt='Imagen del producto'>
                    <div class='card-body'>
                        <h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>
                        <p class='card-text'> " . htmlspecialchars($row['descripcion']) . "</p>
                        <p class='card-text'><b>Precio:</b> $" . htmlspecialchars($row['precio']) . "</p>
                        <button class='btn btn-primary' onclick='addToCart(" . $row['id'] . ", \"" . htmlspecialchars($row['nombre']) . "\", " . $row['precio'] . ")'>Agregar al carrito</button>
                    </div>
                  </div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }

        $conexion->close();
        ?>
    </div>

    <div class="container mt-4">
        <footer>
            <div class="container_footer">
                <div class="item">
                    <p><b>Locales</b></p>
                    <p>Gonzalez Suarez</p>
                    <p>Chimbacalle</p>
                    <p>Magdalena</p>
                </div>
                <div class="item">
                    <p><b>Formas de Pago</b></p>
                    <p>Efectivo</p>
                    <p>Tarjeta de Crédito</p>
                    <p>Tarjeta de débito</p>
                </div>
                <div class="item">
                    <p><b>Ayuda</b></p>
                    <p>Guias</p>
                    <p>Reservas</p>
                </div>
                <div class="item">
                    <p><b>Síganos</b></p>
                    <p>Facebook</p>
                    <p>Instagram</p>
                    <p>Twitter</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cart-items">
                    <!-- Contenido del carrito -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="finalizarCompra()">Finalizar Compra</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializar el carrito si no existe
        if (!localStorage.getItem('cart')) {
            localStorage.setItem('cart', JSON.stringify([]));
        }

        // Función para agregar un producto al carrito
        function addToCart(id, nombre, precio) {
            let cart = JSON.parse(localStorage.getItem('cart'));
            let item = cart.find(producto => producto.id === id);

            if (item) {
                item.cantidad += 1;
            } else {
                cart.push({ id: id, nombre: nombre, precio: precio, cantidad: 1 });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        // Función para actualizar el contador del carrito
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart'));
            let count = cart.reduce((total, item) => total + item.cantidad, 0);
            document.getElementById('cart-count').textContent = count;
        }

        // Llamar a la función para actualizar el contador cuando la página cargue
        window.onload = function () {
            updateCartCount();
        };

        function showCart() {
            let cart = JSON.parse(localStorage.getItem('cart'));
            let cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = ''; // Limpiar contenido previo

            let total = 0;

            if (cart.length > 0) {
                cart.forEach((item, index) => {
                    let subtotal = item.precio * item.cantidad;
                    total += subtotal;
                    cartItems.innerHTML += `
                        <div class="row mb-2">
                            <div class="col-6">${item.nombre}</div>
                            <div class="col-3">Cantidad: ${item.cantidad}</div>
                            <div class="col-3">Precio: $${subtotal.toFixed(2)}</div>
                            <div class="col-12">
                                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Eliminar</button>
                            </div>
                        </div>`;
                });

                cartItems.innerHTML += `
                    <hr>
                    <div class="text-end">
                        <h5>Total: $${total.toFixed(2)}</h5>
                    </div>`;
            } else {
                cartItems.innerHTML = '<p>No hay productos en el carrito.</p>';
            }

            let cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        }

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem('cart'));
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            showCart();
            updateCartCount();
        }

        function finalizarCompra() {
            <?php if ($loggedIn): ?>
                window.location.href = "compra.php";
            <?php else: ?>
                alert('Debe loguearse para realizar la compra.');
            <?php endif; ?>
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
