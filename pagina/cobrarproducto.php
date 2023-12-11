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

    $sqlSelect = "SELECT * FROM cafes_preparados WHERE id = $id";
    $resultSelect = $conexion->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {
        return $resultSelect->fetch_assoc();
    } else {
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cafes']) && is_array($_POST['cafes'])) {
        $cafes = $_POST['cafes'];
        $cantidadTotalVentas = 0; // Variable para almacenar la cantidad total de cafés vendidos
        $productosVendidos = []; // Variable para almacenar los productos comprados

        foreach ($cafes as $idCafe => $cantidad) {
            $ingrediente = obtenerIngredientePorId($idCafe);

            if ($ingrediente && is_numeric($cantidad) && $cantidad > 0) {
                $totalPagar += $ingrediente['precio'] * $cantidad;

                // Actualizar la cantidad total de cafés vendidos
                $cantidadTotalVentas += $cantidad;

                // Almacenar el producto vendido en el array con el ID del café
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

            // Inserción en la tabla de ventas
            $productos = [];
            foreach ($productosVendidos as $producto) {
                $productos[] = $producto['nombre'] . " (ID: " . $producto['id'] . ")";
            }

            // Convertir el array de productos a una cadena
            $productos = implode(", ", $productos);

            // Insertar datos en la tabla de ventas
            $sqlInsertVenta = "INSERT INTO ventas (cantidad_venta, producto, cambio, recibido) VALUES ($cantidadTotalVentas, '$productos', $cambio, $totalCobrado)";
            $resultInsertVenta = $conexion->query($sqlInsertVenta);

            if ($resultInsertVenta) {
                echo "Venta registrada correctamente.";
            } else {
                echo "Error al registrar la venta: " . $conexion->error;
                $mensaje = "Error al registrar la venta: " . $conexion->error;
                $tipoMensaje = "danger";
                
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var notification = document.createElement('div');
                        notification.classList.add('alert', 'alert-$tipoMensaje', 'alert-dismissible', 'fade', 'show', 'position-fixed', 'top-0', 'end-0', 'm-3');
                        notification.innerHTML = `
                            $mensaje
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        `;
                    });
                  
                </script>";
            }
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
    <title>Cobrar Cafés - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <a href="inicio.php" class="back-btn text-decoration-none">&lt; Volver</a>
    <h2 class="mb-4">Cobrar Cafés</h2>

    <form action="" method="post">
        <?php if ($mostrarModal): ?>
            <input type="hidden" name="totalPagar" value="<?php echo $totalPagar; ?>">
        <?php endif; ?>
        <h3>Selecciona los Cafés a Cobrar:</h3>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $ingredientes = obtenerIngredientes();
            foreach ($ingredientes as $ingrediente) {
                echo "<tr>";
                echo "<td>{$ingrediente['nombre_cafe']}</td>";
                echo "<td><input type='number' name='cafes[{$ingrediente['id']}]' min='0' max='{$ingrediente['cantidad_cafe']}'></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Generar Cobro</button>
        <?php if ($mostrarModal): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resumenCobroModal">Mostrar Cobro</button>
        <?php endif; ?>
    </form>
</div>

<div class="modal fade" id="resumenCobroModal" tabindex="-1" aria-labelledby="resumenCobroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumenCobroModalLabel">Resumen de Cobro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Total a Pagar: $<?php echo $totalPagar; ?></h4>

                <form action="" method="post">
                    <input type="hidden" name="totalPagar" value="<?php echo $totalPagar; ?>">
                    <div class="mb-3">
                        <label for="montoPagado" class="form-label">Monto Pagado:</label>
                        <input type="text" class="form-control" name="totalCobrado" value="<?php echo $totalPagar; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="denominaciones" class="form-label">Denominaciones:</label>
                        <div class="row">
                            <?php
                            $denominaciones = [500, 200, 100, 50, 10, 5, 2, 1];
                            $nombresdenominaciones = ['Q','D','C','C','D','C','D','U'];
                            foreach ($denominaciones as $denominacion) {
                                echo '<div class="col">';
                                echo "<p>$$denominacion.00</p>";
                                /* echo "<input type='number'  class='form-control' name='denominacion_$denominacion' placeholder='$denominacion' min='0'>"; */
                                echo "<input type='number'  class='form-control' name='denominacion_$denominacion' min='0'>";
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" name="cobrar" class="btn btn-primary">Calcular Cambio</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-u/YYB4d4WIF5IlXv5bo5F5z5p2YFjz8FEKKZH7oShj5/8G7K8tCrp9lV7bi1b1Hq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
