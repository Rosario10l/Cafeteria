
<?php
include_once('../Config/Conexion.php');

// Función para obtener los ingredientes de la base de datos
function obtenerIngredientesA() {
    global $conexion;
    $sqlSelect = "SELECT * FROM almacen";
    $resultSelect = $conexion->query($sqlSelect);
    $ingredientes = [];

    while ($fila = $resultSelect->fetch_assoc()) {
        $ingredientes[] = $fila;
    }

    return $ingredientes;
}

function actualizarCantidadAlmacen($id, $nuevaCantidad) {
    global $conexion;

    $sqlUpdate = "UPDATE almacen SET cantidad_almacen = cantidad_almacen - $nuevaCantidad WHERE id = $id";
    $resultUpdate = $conexion->query($sqlUpdate);

    return $resultUpdate;
}

function obtenerIngredientes() {
    global $conexion;
    $sqlSelect = "SELECT * FROM cafes_preparados";
    $resultSelect = $conexion->query($sqlSelect);
    $ingredientes = [];

    while ($fila = $resultSelect->fetch_assoc()) {
        $ingredientes[] = $fila;
    }

    return $ingredientes;
}

// Función para actualizar la cantidad y el precio de un ingrediente
function actualizarCantidad($id, $nuevaCantidad, $nuevoPrecio) {
    global $conexion;

    $sqlUpdate = "UPDATE cafes_preparados SET cantidad_cafe = cantidad_cafe + $nuevaCantidad, precio = $nuevoPrecio WHERE id = $id";
    
    $resultUpdate = $conexion->query($sqlUpdate);

    return $resultUpdate;
}

// Función para agregar un nuevo café
function agregarCafe($nombre, $precio, $imagen) {
    global $conexion;

    $sqlInsert = "INSERT INTO cafes_preparados (nombre_cafe, cantidad_cafe, precio, imagen) VALUES ('$nombre', 0, $precio, '$imagen')";
    $resultInsert = $conexion->query($sqlInsert);

    return $resultInsert;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_id"])) {
        $id = $_POST["update_id"];
        $nuevaCantidad = $_POST["nueva_cantidad"];
        $nuevoPrecio = $_POST["precio"];

        if (is_numeric($nuevaCantidad) && $nuevaCantidad >= 0 && is_numeric($nuevoPrecio) && $nuevoPrecio >= 0) {
            $resultUpdate = actualizarCantidad($id, $nuevaCantidad, $nuevoPrecio);

            if ($resultUpdate) {
                echo "<script>
            toastr.success('Cantidad y precio actualizados exitosamente.', 'Éxito', { timeOut: 5000 });
            if (confirm('¿Quieres continuar?')) {
                // Código adicional si se acepta
            } else {
                toastr.info('Operación cancelada.', 'Información');
            }
          </script>";
            } else {
                echo "<script>
                        toastr.error('Error al actualizar la cantidad y el precio.');
                      </script>";
            }
        } else {
            echo "<script>
                    toastr.error('La cantidad y el precio deben ser números positivos.');
                  </script>";
        }
    }elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["nombre_cafe"]) && isset($_POST["precio"]) && isset($_POST["imagen"])) {
        $nombre = $_POST["nombre_cafe"];
        $precio = $_POST["precio"];
        $imagen = $_POST["imagen"];
        

        // Verificar que se haya seleccionado al menos un ingrediente
        if (!empty($nombre) && is_numeric($precio) && $precio >= 0) {
            // Agregar el nuevo café
            $resultInsert = agregarCafe($nombre, $precio, $imagen);

            if ($resultInsert) {
                $idNuevoCafe = $conexion->insert_id;

                // Recorrer los ingredientes seleccionados y descontarlos del almacen
                foreach ($_POST["ingredientes"] as $idIngrediente => $cantidadIngrediente) {
                    // Verificar que la cantidad sea válida
                    if (is_numeric($cantidadIngrediente) && $cantidadIngrediente > 0) {
                        // Iniciar transacción
                        $conexion->begin_transaction();

                        // Actualizar la cantidad en la tabla de cafés preparados
                        $resultUpdate = actualizarCantidad($idNuevoCafe, $cantidadIngrediente, $precio);

                        // Descontar la cantidad del almacen
                        $resultDescuento = actualizarCantidadAlmacen($idIngrediente, $cantidadIngrediente);

                        // Verificar el éxito de ambas operaciones
                        if ($resultUpdate && $resultDescuento) {
                            // Confirmar la transacción
                            $conexion->commit();

                            echo "<script>
                                    if (confirm('Ingrediente agregado exitosamente. ¿Quieres continuar?')) {
                                        // Código adicional si se acepta
                                    } else {
                                        // Código adicional si se cancela
                                    }
                                  </script>";
                        } else {
                            // Revertir la transacción en caso de error
                            $conexion->rollback();

                            echo "<script>
                                    toastr.error('Error al actualizar la cantidad y el precio.');
                                  </script>";
                            // Puedes agregar más manejo de errores aquí si es necesario
                            exit;
                        }
                    } else {
                        echo "<script>
                                toastr.error('La cantidad de ingredientes debe ser un número positivo.');
                              </script>";
                        // Puedes agregar más manejo de errores aquí si es necesario
                        exit;
                    }
                }
            } else {
                echo "<script>
                        toastr.error('Error al agregar el ingrediente.');
                      </script>";
            }
        } else {
            echo "<script>
                    toastr.error('Ingresa un nombre válido y un precio positivo.');
                  </script>";
        }
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
    <h2 class="mb-4">Preparar Cafe</h2>

    <a href="#" data-bs-toggle="modal" data-bs-target="#agregarIngredienteModal" class="btn btn-primary">Nuevo cafe</a>

    <div class="modal fade" id="agregarIngredienteModal" tabindex="-1" aria-labelledby="agregarIngredienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarIngredienteModalLabel">Agregar Cafe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Café:</label>
                            <input type="text" class="form-control" name="nombre_cafe" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label>
                            <input type="number" class="form-control" name="precio" required min="0" max="999">
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen URL:</label>
                            <input type="text" class="form-control" name="imagen" required>
                        </div>
                        <div class="mb-3">
                            <label for="ingredientes" class="form-label">Selecciona los ingredientes y cantidades:</label>
                            <?php
                            $ingredientes = obtenerIngredientesA();
                            foreach ($ingredientes as $ingrediente) {
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='checkbox' name='ingredientes[{$ingrediente['id']}]'>";
                                echo "<label class='form-check-label' for='ingredientes[{$ingrediente['id']}]'>{$ingrediente['ingrediente']}</label>";
                                echo "<input type='number' class='form-control' name='cantidades[{$ingrediente['id']}]' min='0'>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Café</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-4">Cafes Preparados</h3>
   
    <a href="#" data-bs-toggle="modal" data-bs-target="#modificaringrediente" class="btn btn-primary">Nuevo cafe</a>
    <div class="modal fade" id="modificaringrediente" tabindex="-1" aria-labelledby="agregarIngredienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header  mb-3">
                    <label for="select_ingrediente" class="col-form-label">Selecciona el cafe:</label>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Café:</label>
                            <select class="form-select" name="update_id" required min="0" max="999">
                                <?php
                                $ingredientes = obtenerIngredientes();
                                foreach ($ingredientes as $ingrediente) {
                                    echo "<option value='{$ingrediente['id']}'>{$ingrediente['nombre_cafe']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nueva_cantidad" class="col-form-label">Agregar Cafe:</label>
                            <input type="number" class="form-control" name="nueva_cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label>
                            <input type="number" class="form-control" name="precio" required min="0" max="999">
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Disponibles</th>
                <th>Precio</th>
               
            </tr>
        </thead>
        <tbody>
            <?php
            $ingredientes = obtenerIngredientes();
            foreach ($ingredientes as $ingrediente) {
                echo "<tr>";
                echo "<td>{$ingrediente['nombre_cafe']}</td>";
                echo "<td>{$ingrediente['cantidad_cafe']}</td>";
                echo "<td>{$ingrediente['precio']}</td>";
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
