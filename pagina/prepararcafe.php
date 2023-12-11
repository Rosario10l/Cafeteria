<?php
include_once('../Config/Conexion.php');

$mostrarModal = false;
$totalPagar = 0;

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

function obtenerIngredientePorId($id) {
    global $conexion;

    $sqlSelect = "SELECT * FROM cafes_preparados WHERE id = ?";
    $stmt = $conexion->prepare($sqlSelect);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultSelect = $stmt->get_result();

    if ($resultSelect->num_rows > 0) {
        return $resultSelect->fetch_assoc();
    } else {
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cafes']) && is_array($_POST['cafes'])) {
        $cafes = $_POST['cafes'];
        $cantidadTotalVentas = 0;
        $productosVendidos = [];

        foreach ($cafes as $idCafe => $cantidad) {
            $ingrediente = obtenerIngredientePorId($idCafe);

            if ($ingrediente && is_numeric($cantidad) && $cantidad > 0) {
                $totalPagar += $ingrediente['precio'] * $cantidad;
                $cantidadTotalVentas += $cantidad;
                $productosVendidos[] = [
                    'id' => $ingrediente['id'],
                    'nombre' => $ingrediente['nombre_cafe'],
                ];
            } else {
                echo "Error: Cantidad no válida para el café seleccionado.";
            }
        }

        $mostrarModal = true;
    }

    if (isset($_POST['cobrar'])) {
        $totalCobrado = $_POST['totalPagar'];

        $totalDenominacionesIngresadas = 0;
        $denominaciones = [500, 200, 100, 50, 10, 5, 2, 1];

        foreach ($denominaciones as $denominacion) {
            $cantidad = isset($_POST["denominacion_$denominacion"]) ? $_POST["denominacion_$denominacion"] : 0;
            $totalDenominacionesIngresadas += intval($denominacion) * intval($cantidad);
        }

        $cambio = $totalDenominacionesIngresadas - $totalCobrado;

        if ($cambio < 0) {
            echo "Falta dinero. Por favor, ingrese la cantidad correcta.";
        } elseif ($cambio == 0) {
            echo "Pago exacto. Gracias por su compra.";

            // Insertar datos en la tabla de ventas
            $productos = [];
            foreach ($productosVendidos as $producto) {
                $productos[] = $producto['nombre'] . " (ID: " . $producto['id'] . ")";
            }

            $productosStr = implode(", ", $productos);

            $sqlInsertVenta = "INSERT INTO ventas (cantidad_venta, producto, cambio, recibido) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sqlInsertVenta);
            $stmt->bind_param("isdd", $cantidadVentas, $productosStr, $cambio, $totalCobrado);
            $resultInsertVenta = $stmt->execute();
        
            return $resultInsertVenta;
        } else {
            echo "Resumen de la transacción:<br>";
            echo "Total cobrado: $totalCobrado<br>";
            echo "Dinero ingresado: $totalDenominacionesIngresadas<br>";
            echo "Cambio en denominaciones:<br>";

            foreach ($denominaciones as $denominacion) {
                $cantidad = floor($cambio / $denominacion);
                if ($cantidad > 0) {
                    echo "$denominacion: $cantidad<br>";
                    $cambio -= $denominacion * $cantidad;
                }
            }

            if ($cambio > 0) {
                echo "Cambio insuficiente. Por favor, ingrese la cantidad exacta.";
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
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" name="cantidad_cafe" required min="0" max="999">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label>
                            <input type="number" class="form-control" name="precio" required min="0" max="999">
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen URL:</label>
                            <input type="text" class="form-control" name="imagen" required>
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
