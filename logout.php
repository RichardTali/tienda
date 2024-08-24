// logout.php
<?php
session_start();
session_destroy(); // Destruir todas las variables de sesión
header("Location: index.php"); // Redirigir al formulario de login
exit();
?>
