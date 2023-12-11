<?php
include_once('../Config/Conexion.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $denominacion = $_POST["denominacion"];
    $cantidad = $_POST["cantidad"];


    if (is_numeric($cantidad) && $cantidad >= 0) {
        
        $sqlSelect = "SELECT cantidad FROM caja WHERE denominacion = $denominacion";
        $resultSelect = $conexion->query($sqlSelect);
        $filaCaja = $resultSelect->fetch_assoc();
        $cantidadActual = $filaCaja['cantidad'];

      
        $nuevaCantidad = $cantidadActual + $cantidad;

        $sqlUpdate = "UPDATE caja SET cantidad = $nuevaCantidad WHERE denominacion = $denominacion";
        $resultUpdate = $conexion->query($sqlUpdate);

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


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["monto_apertura"])) {
    $montoApertura = intval($_POST["monto_apertura"]);
    echo "<script>
            toastr.info('Se ha realizado la apertura con un monto de $montoApertura.');
          </script>";
}
$sqlCaja = "SELECT * FROM caja";
$resultCaja = $conexion->query($sqlCaja);
$dineroTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinero en Caja - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<div class="card-body">
<div class="container mt-5">
    <a href="inicio.php" class="back-btn text-decoration-none">&lt; Volver</a>
    <h2 class="mb-4">Agregar cambio</h2>

  
    <form action="" method="post" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="denominacion" class="col-form-label">Denominación:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" name="denominacion" required>
                    <?php
                    while ($filaCaja = $resultCaja->fetch_assoc()) {
                        $denominacionActual = $filaCaja['denominacion'];
                        echo "<option value='$denominacionActual'>$denominacionActual</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-auto">
                <label for="cantidad" class="col-form-label">Cantidad:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" name="cantidad" required  min="0" max="999">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Agregar Cantidad</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Denominación</th>
                <th>Cantidad</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $resultCaja->data_seek(0);
        while ($filaCaja = $resultCaja->fetch_assoc()) {
            $denominacion = $filaCaja['denominacion'];
            $cantidad = $filaCaja['cantidad'];

            $valorDenominacion = $denominacion * $cantidad;
            $dineroTotal += $valorDenominacion;
   
            echo "<tr>";
            echo "<td>$denominacion</td>";
            echo "<td>$cantidad</td>";
            echo "<td>$valorDenominacion</td>";
            echo "</tr>";
        }
        ?>

        </tbody>
    </table>

    <p class="fw-bold mt-3">Dinero actual en la caja: $<?php echo $dineroTotal; ?></p>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-u/YYB4d4WIF5IlXv5bo5F5z5p2YFjz8FEKKZH7oShj5/8G7K8tCrp9lV7bi1b1Hq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
