<?php
require 'config/conexion.php';

$sql = "select id, nombre, email, password, direccion, telefono,fecha_registro,rol from usuarios";
$result = $conexion->query($sql);



?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Para utilizar Google Fonts para los iconos -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">    
</head>

<body>

    <div class="container mt-4">
        <h1>Listado de Usuarios</h1>
        <br>
        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">Registrar</a>

        
        


        <br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Password</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Fecha Registro</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                    
                </tr>
            </thead>
            <tbody>
                
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <th scope='row'>" . $row["id"] . "</th>
                        <td>" . $row["nombre"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td>" . $row["password"] . "</td>
                        <td>" . $row["direccion"] . "</td>
                        <td>" . $row["telefono"] . "</td>
                        <td>" . $row["fecha_registro"] . "</td>
                        <td>" . $row["rol"] . "</td>
                        
                        <td>
                            
                            <button class='btn btn-outline-primary btn-sm' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#editarModal' 
                                    data-id='" . $row["id"] . "' 
                                    data-nombre='" . htmlspecialchars($row["nombre"]) . "'>
                                <span class='material-icons'>edit</span>
                            </button>

                            
                            <a href='crudUsuarios/eliminar.php?id=" . $row["id"] . "' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
                                <span class='material-icons'>delete</span>
                            </a>
                        </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay datos</td></tr>";
                }



                $conexion->close();




                ?>
                
            </tbody>
        </table>
    </div>

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
                    <form action="crudUsuarios/registrar.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el nombre">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese el email">
                        </div>
                        <div class="mb-3">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Ingrese la contraseña">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Direccion</label>
                            <input type="direccion" class="form-control" name="direccion" id="direccion" placeholder="Ingrese su direccion">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="telefono" class="form-control" name="telefono" id="telefono" placeholder="Ingrese su telefono">
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
                    <form id="editarForm" method="post">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!--script para llamar al editar.php-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editarModal = document.getElementById('editarModal');
            const form = document.getElementById('editarForm');

            editarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                const idField = document.getElementById('editId');
                const nombreField = document.getElementById('editNombre');

                idField.value = id;
                nombreField.value = nombre;

                form.action = 'crudCategorias/editar.php?id=' + id;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>