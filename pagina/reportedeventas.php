<?php
// generar_reporte.php

// Incluye el archivo de conexión a la base de datos
include_once('../Config/Conexion.php');

// Inicializa una variable para el contenido de la tabla
$tablaVentas = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtén el ID del cajero seleccionado desde el formulario
    $idCajero = $_POST["cajero"];

    // Consulta para obtener las ventas asociadas al cajero
    $sqlVentas = "SELECT * FROM ventas WHERE id_cajero = $idCajero";
    $resultVentas = $conexion->query($sqlVentas);

    // Verifica si hay ventas
    if ($resultVentas->num_rows > 0) {
        $tablaVentas .= "<h3>Ventas realizadas por el cajero:</h3>";
        $tablaVentas .= "<table border='1'>";
        $tablaVentas .= "<tr><th>ID Venta</th><th>Total</th><th>Fecha</th></tr>";

        // Muestra las ventas en la tabla
        while ($venta = $resultVentas->fetch_assoc()) {
            $tablaVentas .= "<tr>";
            $tablaVentas .= "<td>" . $venta['id_venta'] . "</td>";
            $tablaVentas .= "<td>" . $venta['total'] . "</td>";
            $tablaVentas .= "<td>" . $venta['fecha'] . "</td>";
            $tablaVentas .= "</tr>";
        }

        $tablaVentas .= "</table>";
    } else {
        // Si no hay ventas, muestra un mensaje
        $tablaVentas .= "<p>No existen ventas realizadas por el cajero seleccionado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
<a href="inicio.php" class="back-btn text-decoration-none">&lt; Volver</a>
    <h2>Informe de Ventas</h2>
    
    <form action="reportedeventas.php" method="post">
        <div class="mb-3">
            
            <label for="cajero" class="form-label">Seleccione el cajero:</label>
            
            <?php
            // Conectar a la base de datos (ajusta la conexión según tu entorno)
            include_once('../Config/Conexion.php');

            // Obtener los cajeros (usuarios con rol 2)
            $sql = "SELECT id, usuario FROM usuarios WHERE rol = 2";
            $result = $conexion->query($sql);

            // Mostrar la lista desplegable con los cajeros
            echo '<select class="form-select" name="cajero" required>';
            echo '<option value="" disabled selected>Seleccione un cajero</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['usuario'] . '</option>';
            }
            echo '</select>';
            ?>
            
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>

    <!-- Muestra la tabla de ventas -->
    <?php echo $tablaVentas; ?>

</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-u/YYB4d4WIF5IlXv5bo5F5z5p2YFjz8FEKKZH7oShj5/8G7K8tCrp9lV7bi1b1Hq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
