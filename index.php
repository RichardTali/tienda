<?php
session_start();

// Controlar la caché para asegurar que la página no se muestre en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
        .cart-icon {
            position: fixed;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .alert {
            display: none;
        }



       
    .card {
        width: 300px; /* Ajusta el ancho de la tarjeta */
        height: 400px; /* Ajusta la altura de la tarjeta */
        margin: 10px;
        display: inline-block;
        vertical-align: top; /* Alinea verticalmente las tarjetas en la misma línea */
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        overflow: hidden; /* Asegura que el contenido no se desborde */
        margin-bottom: 50px;
    }

    .card-img-top {
    width: 150px; /* Ancho de la imagen */
    height: 150px; /* Altura de la imagen */
    object-fit: cover; /* Ajusta la imagen para que cubra el área sin distorsión */
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-top: 10px; /* Margen superior para espaciar la imagen del borde superior de la tarjeta */
}



    .card-body {
        padding: 15px; /* Espacio dentro de la tarjeta */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Distribuye el espacio entre el contenido y el botón */
        height: calc(100% - 200px); /* Ajusta la altura del cuerpo de la tarjeta para que ocupe el espacio restante */
    }





    .container_footer {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Cuatro columnas de igual ancho */
    margin-bottom: 20px;
    
    
}

.item {
    
    text-align: center;
    font-size: 1.5em;
}

    </style>
</head>

<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['nombre'])): ?>
            <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>!</p>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        <?php endif; ?>

        <h1>Productos Disponibles</h1>

        <div class="cart-icon">
            <i class="fas fa-shopping-cart" onclick="showCart()"></i>
            <span id="cart-count" class="badge bg-danger">0</span>
        </div>

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
            <h1>Culminacion</h1>
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
        <p><b>Siganos</b></p>
        <p>Facebook</p>
        <p>Instagram</p>
        <p>Twitter</p>    
        </div>
        
    </div>
        </footer>

    </div>







    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cart-items">
                    <!-- Aquí se mostrarán los productos del carrito -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Finalizar Compra</button>
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

        // Llamar a la función para mostrar el conteo actual del carrito al cargar la página
        updateCartCount();
    </script>





    <script>

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
                    <div class="col-12 text-end mt-2">
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Quitar</button>
                    </div>
                </div>
            `;
                });

                // Agregar total al final de la lista de productos
                cartItems.innerHTML += `
            <div class="row mt-3">
                <div class="col-6"><strong>Total</strong></div>
                <div class="col-6 text-end"><strong>$${total.toFixed(2)}</strong></div>
            </div>
        `;
            } else {
                cartItems.innerHTML = '<p>No hay productos en el carrito.</p>';
            }

            // Mostrar el modal
            let cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        }

       

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart'));
            let count = cart.reduce((total, item) => total + item.cantidad, 0);
            document.getElementById('cart-count').textContent = count;
        }

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
                    <div class="col-12 text-end mt-2">
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Quitar</button>
                    </div>
                </div>
            `;
            });

            // Agregar total al final de la lista de productos
            cartItems.innerHTML += `
            <div class="row mt-3">
                <div class="col-6"><strong>Total</strong></div>
                <div class="col-6 text-end"><strong>$${total.toFixed(2)}</strong></div>
            </div>
        `;
        } else {
            cartItems.innerHTML = '<p>No hay productos en el carrito.</p>';
        }

        // Mostrar el modal
        let cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
        cartModal.show();




    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>