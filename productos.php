<?php

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}

require 'config/conexion.php';



$sql = "select p.id, p.nombre, p.descripcion, p.precio,p.cantidad,p.imagen,p.categoria_id,c.nombre AS categoria
        from productos p 
        join categorias c ON p.categoria_id = c.id;";


$result = $conexion->query($sql);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    

    <!-- Para utilizar Google Fonts para los iconos -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h1>Listado de Productos</h1>
        <br>
        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">Registrar</a>

        <br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <th scope='row'>" . htmlspecialchars($row["id"]) . "</th>
                    <td>" . htmlspecialchars($row["nombre"]) . "</td>
                    <td>" . htmlspecialchars($row["descripcion"]) . "</td>
                    <td>" . htmlspecialchars($row["precio"]) . "</td>
                    <td>" . htmlspecialchars($row["cantidad"]) . "</td>
                    <td>" . htmlspecialchars($row["categoria"]) . "</td>
                    <td>
                        <img src='imagenes/" . htmlspecialchars($row["imagen"]) . "' alt='Imagen del producto' width='100' height='100'>
                    </td>

                    <td>
                        <button class='btn btn-outline-primary btn-sm' 
                                data-bs-toggle='modal' 
                                data-bs-target='#editarModal' 
                                data-id='" . htmlspecialchars($row["id"]) . "' 
                                data-nombre='" . htmlspecialchars($row["nombre"]) . "'
                                data-descripcion='" . htmlspecialchars($row["descripcion"]) . "'
                                data-precio='" . htmlspecialchars($row["precio"]) . "'
                                data-cantidad='" . htmlspecialchars($row["cantidad"]) . "'
                                data-categoria_id='" . htmlspecialchars($row["categoria_id"]) . "'
                                data-imagen='" . htmlspecialchars($row["imagen"]) . "'>
                            <span class='material-icons'>edit</span>
                        </button>
                        <a href='crudProductos/eliminar.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
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



    <?php
    require 'config/conexion.php';

    $sql_categorias = "Select id, nombre from categorias";
    $result_categorias = $conexion->query($sql_categorias);

    if ($result_categorias === false) {
        echo "Error al obtener categorias: " . $conexion->error;
        exit();
    }

    $categorias = [];
    while ($row_categoria = $result_categorias->fetch_assoc()) {
        $categorias[] = $row_categoria;
    }


    ?>

    <!--Modal para el registro-->

    <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de Registro -->
                    <form action="crudProductos/registrar.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                placeholder="Ingrese el nombre">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion"
                                placeholder="Ingrese la descripcion">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="text" class="form-control" name="precio" id="precio"
                                placeholder="Ingrese el precio">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidad"
                                placeholder="Ingrese la cantidad">
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select class="form-select" name="categoria_id" id="categoria">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= htmlspecialchars($categoria['id']) ?>">
                                        <?= htmlspecialchars($categoria['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="imagen"
                                placeholder="Ingrese la imagen">
                        </div>
                        <!-- Añadir otros campos según sea necesario -->
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="editarForm" method="post" enctype="multipart/form-data" action="crudProductos/editar.php">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" name="descripcion" id="editDescripcion"
                                placeholder="Ingrese la descripcion">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" name="precio" id="editPrecio"
                                placeholder="Ingrese el precio">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" id="editCantidad"
                                placeholder="Ingrese la cantidad">
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select class="form-select" name="categoria_id" id="editCategoria">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= htmlspecialchars($categoria['id']) ?>">
                                        <?= htmlspecialchars($categoria['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editImagen" class="form-label">Cambiar Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="editImagen">
                        </div>


                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!--script para llamar al editar.php-->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const editarModal = document.getElementById('editarModal'); // Asegúrate de que 'editarModal' exista en el DOM
    const form = document.querySelector('#editarModal form'); // Selecciona el formulario dentro del modal

    editarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const descripcion = button.getAttribute('data-descripcion');
        const precio = button.getAttribute('data-precio');
        const cantidad = button.getAttribute('data-cantidad');
        const categoriaId = button.getAttribute('data-categoria_id');
        const imagenUrl = button.getAttribute('data-imagen');

        document.getElementById('editId').value = id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('editPrecio').value = precio;
        document.getElementById('editCantidad').value = cantidad;
        document.getElementById('editCategoria').value = categoriaId;

        const currentImage = document.getElementById('currentImage');
        if (currentImage) {
            currentImage.src = "../imagenes/" + imagenUrl;
        }

        form.action = 'crudProductos/editar.php';
    });
});

</script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>