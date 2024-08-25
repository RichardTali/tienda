<?php
session_start();
require 'config/conexion.php'; // Incluye la conexión a la base de datos

if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_SESSION['nombre'];
    $cart = json_decode($_POST['cart'], true); // Asume que el carrito se envía como JSON
    
    if (empty($cart)) {
        echo "El carrito está vacío.";
        exit;
    }

    // Calcula el subtotal y el total
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }
    $total = $subtotal; // Aquí puedes agregar impuestos u otros cargos si es necesario

    // Inserta la compra en la tabla compras
    $stmt = $conexion->prepare("INSERT INTO compras (nombre_usuario, subtotal, total) VALUES (?, ?, ?)");
    $stmt->bind_param("sdd", $nombre_usuario, $subtotal, $total);
    $stmt->execute();
    $compra_id = $stmt->insert_id; // Obtén el ID de la compra recién insertada
    $stmt->close();

    // Inserta los detalles de la compra en la tabla detalles_compra
    $stmt = $conexion->prepare("INSERT INTO detalles_compra (compra_id, producto_id, nombre_producto, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $subtotal_item = $item['precio'] * $item['cantidad'];
        $stmt->bind_param("iisdid", $compra_id, $item['id'], $item['nombre'], $item['cantidad'], $item['precio'], $subtotal_item);
        $stmt->execute();
    }
    $stmt->close();

    // Redirige a una página de confirmación o muestra un mensaje de éxito
    echo "Compra realizada con éxito.";
} else {
    echo "Método de solicitud no permitido.";
}

$conexion->close();
?>
