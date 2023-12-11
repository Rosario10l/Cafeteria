<?php
// Conexión a la base de datos (ajusta los detalles según tu configuración)
include_once('../Config/Conexion.php');

// Función para obtener los ingredientes de la base de datos
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se envió el formulario de cobro
    if (isset($_POST["montoPagado"])) {
        // Obtener el monto pagado
        $montoPagado = $_POST["montoPagado"];

        // Obtener las denominaciones ingresadas
        $denominaciones = [
            500 => $_POST["denominacion_500"],
            200 => $_POST["denominacion_200"],
            100 => $_POST["denominacion_100"],
            50 => $_POST["denominacion_50"],
            10 => $_POST["denominacion_10"],
            5 => $_POST["denominacion_5"],
            2 => $_POST["denominacion_2"]
        ];

        // Calcular el total ingresado en denominaciones
        $totalDenominaciones = 0;
        foreach ($denominaciones as $denominacion => $cantidad) {
            $totalDenominaciones += $denominacion * $cantidad;
        }

        // Puedes continuar aquí con la lógica para calcular el cambio y actualizar la base de datos

        // Mostrar el resultado (puedes adaptar esta parte según tus necesidades)
        echo "Monto Pagado: $montoPagado<br>";
        echo "Total Denominaciones: $totalDenominaciones<br>";

        // Calcular el cambio
        $cambio = $totalDenominaciones - $montoPagado;

        echo "Cambio: $cambio";
    }
}
?>
