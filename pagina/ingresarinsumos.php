<?php
include_once('../Config/Conexion.php');

// Función para obtener los ingredientes de la base de datos
function obtenerIngredientes() {
    global $conexion;
    $sqlSelect = "SELECT * FROM almacen";
    $resultSelect = $conexion->query($sqlSelect);
    $ingredientes = [];

    while ($fila = $resultSelect->fetch_assoc()) {
        $ingredientes[] = $fila;
    }

    return $ingredientes;
}

// Función para actualizar la cantidad de un ingrediente
function actualizarCantidad($id, $nuevaCantidad) {
    global $conexion;

    $sqlUpdate = "UPDATE almacen SET cantidad_almacen = cantidad_almacen + $nuevaCantidad WHERE id = $id";
    $resultUpdate = $conexion->query($sqlUpdate);

    return $resultUpdate;
}

// Procesar el formulario de ingreso de ingredientes
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_id"])) {
        $id = $_POST["update_id"];
        $nuevaCantidad = $_POST["nueva_cantidad"];

        if (is_numeric($nuevaCantidad) && $nuevaCantidad >= 0) {
            $resultUpdate = actualizarCantidad($id, $nuevaCantidad);

            if ($resultUpdate) {
                echo "<script>
                        toastr.success('Cantidad actualizada exitosamente.');
                      </script>";
            } else {
                echo "<script>
                        toastr.error('Error al actualizar la cantidad.');
                      </script>";
            }
        } else {
            echo "<script>
                    toastr.error('La cantidad debe ser un número positivo.');
                  </script>";
        }
    }
    elseif (isset($_POST["ingrediente"]) && isset($_POST["cantidad_almacen"])) {
        $nombre = $_POST["ingrediente"];
        $cantidad = $_POST["cantidad_almacen"];

        if (!empty($nombre) && is_numeric($cantidad) && $cantidad >= 0) {
            $sqlInsert = "INSERT INTO almacen (ingrediente, cantidad_almacen) VALUES ('$nombre', $cantidad)";
            $resultInsert = $conexion->query($sqlInsert);

            if ($resultInsert) {
                      $mensaje = "Ingrediente agregado exitosamente";
                      $tipoMensaje = "success";
                      
                      echo "<script>
                          document.addEventListener('DOMContentLoaded', function() {
                              var notification = document.createElement('div');
                              notification.classList.add('alert', 'alert-$tipoMensaje', 'alert-dismissible', 'fade', 'show', 'position-fixed', 'top-0', 'end-0', 'm-3');
                              notification.innerHTML = `
                                  $mensaje
                                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              `;
                              document.body.appendChild(notification);
                              setTimeout(function() {
                                  notification.remove();
                              }, 2000);
                          });
                        
                      </script>";
            } else {
                
                      $mensaje = "Error al agregar el ingrediente.";
                      $tipoMensaje = "danger";
                      
                      echo "<script>
                          document.addEventListener('DOMContentLoaded', function() {
                              var notification = document.createElement('div');
                              notification.classList.add('alert', 'alert-$tipoMensaje', 'alert-dismissible', 'fade', 'show', 'position-fixed', 'top-0', 'end-0', 'm-3');
                              notification.innerHTML = `
                                  $mensaje
                                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              `;
                              document.body.appendChild(notification);
                              setTimeout(function() {
                                  notification.remove();
                              }, 2000);
                          });
                        
                      </script>";



            }
        } else {
            echo '<script>
                    $.notify({
                        icon: "glyphicon glyphicon-alert",
                        title: "Error",
                        message: "Ingresa un nombre válido y una cantidad positiva."
                    },{
                        type: "danger"
                    });
                  </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar/Actualizar Ingredientes - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <a href="inicio.php" class="back-btn text-decoration-none">&lt; Volver</a>
    <h2 class="mb-4">Ingresar/Actualizar Ingredientes</h2>

 <div class="d-flex justify-content-end"> 
 <a href="#" data-bs-toggle="modal" data-bs-target="#agregarIngredienteModal" class="btn btn-primary">Agregar Nuevo Ingrediente</a>

 </div>
   
    <div class="modal fade" id="agregarIngredienteModal" tabindex="-1" aria-labelledby="agregarIngredienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarIngredienteModalLabel">Agregar Ingrediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Ingrediente:</label>
                            <input type="text" class="form-control" name="ingrediente" required  min="0" max="999">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" name="cantidad_almacen" required  min="0" max="999">
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Ingrediente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Ingredientes Disponibles</h3>
    <form action="" method="post">
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label for="select_ingrediente" class="col-form-label">Seleccionar Ingrediente:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" name="update_id" required  min="0" max="999">
                    <?php
                    $ingredientes = obtenerIngredientes();
                    foreach ($ingredientes as $ingrediente) {
                        echo "<option value='{$ingrediente['id']}'>{$ingrediente['ingrediente']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-auto">
                <label for="nueva_cantidad" class="col-form-label">Nueva Cantidad:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" name="nueva_cantidad" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-warning">Actualizar Cantidad</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ingredientes as $ingrediente) {
                echo "<tr>";
                echo "<td>{$ingrediente['ingrediente']}</td>";
                echo "<td>{$ingrediente['cantidad_almacen']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-u/YYB4d4WIF5IlXv5bo5F5z5p2YFjz8FEKKZH7oShj5/8G7K8tCrp9lV7bi1b1Hq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
