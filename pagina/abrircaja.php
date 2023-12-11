<?php
session_start();
include('../Config/Conexion.php');
$mostrarFormulario = false;
$mensajeError = "";

if (!isset($_SESSION['montoApertura'])) {
    $_SESSION['montoApertura'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["monto_apertura"])) {
        $mostrarFormulario = true;
        $_SESSION['montoApertura'] = intval($_POST["monto_apertura"]);
        $_SESSION['caja_abierta'] = true;

        $mensajeApertura = "Monto de apertura ingresado: $" . $_SESSION['montoApertura'];
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var notificationApertura = document.createElement('div');
                notificationApertura.classList.add('alert', 'alert-success', 'alert-dismissible', 'fade', 'show', 'position-fixed', 'top-0', 'end-0', 'm-3');
                notificationApertura.innerHTML = `
                    $mensajeApertura
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                `;
                document.body.appendChild(notificationApertura);
    
                notificationApertura.querySelector('.btn-close').addEventListener('click', function() {
                    notificationApertura.remove();
                });
            });
        </script>";
    }

    if (isset($_POST["denominaciones"])) {
        $denominaciones = $_POST["denominaciones"];
        $sumaDenominaciones = 0;

        $valoresDenominaciones = [
            500 => 500,
            200 => 200,
            100 => 100,
            50  => 50,
            10  => 10,
            5   => 5,
            2   => 2
        ];

        foreach ($denominaciones as $denominacion => $cantidad) {
            if (isset($valoresDenominaciones[$denominacion])) {
                $sumaDenominaciones += $valoresDenominaciones[$denominacion] * $cantidad;
            }
        }

        if ($_SESSION['montoApertura'] !== $sumaDenominaciones) {
            $mensaje = "La suma de denominaciones no coincide con el monto de apertura.";
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
                    }, 1000);
                });
              
            </script>";
        } else {
            $queryUpdate = "UPDATE caja SET cantidad = 
                            CASE 
                                WHEN denominacion = 500 THEN $denominaciones[500]
                                WHEN denominacion = 200 THEN $denominaciones[200]
                                WHEN denominacion = 100 THEN $denominaciones[100]
                                WHEN denominacion = 50  THEN $denominaciones[50]
                                WHEN denominacion = 10  THEN $denominaciones[10]
                                WHEN denominacion = 5   THEN $denominaciones[5]
                                WHEN denominacion = 2   THEN $denominaciones[2]
                            END";

            mysqli_query($conexion, $queryUpdate);

            $mensaje = "La apertura de caja es correcta";
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
                    }, 5000);
                });
                setTimeout(function() {
                    window.location.href = 'inicio.php';
                }, 1000);
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apertura de Caja - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">

    <h2 class="mb-4">Apertura de Caja - Cafetería</h2>
    <?php if (!$mostrarFormulario): ?>
    <form action="" method="post" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="monto_apertura" class="col-form-label">Monto de Apertura:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" name="monto_apertura" required  min="0" max="10000">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Ingresar monto</button>
            </div>
        </div>
    </form>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
    <form action="abrircaja.php" method="post">
    <div class="row g-3 align-items-center">
        <div class="col-md-2">
            <label class="col-form-label">500</label>
            <input type="number" class="form-control" name="denominaciones[500]" value="0" required  min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="col-form-label">200</label>
            <input type="number" class="form-control" name="denominaciones[200]" value="0" required min="0" max="999"> 
        </div>
        <div class="col-md-2">
            <label class="col-form-label">100</label>
            <input type="number" class="form-control" name="denominaciones[100]" value="0" required  min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="col-form-label">50</label>
            <input type="number" class="form-control" name="denominaciones[50]" value="0" required  min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="col-form-label">10</label>
            <input type="number" class="form-control" name="denominaciones[10]" value="0" required  min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="col-form-label">5</label>
            <input type="number" class="form-control" name="denominaciones[5]" value="0" required  min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="col-form-label">2</label>
            <input type="number" class="form-control" name="denominaciones[2]" value="0" required  min="0" max="999">
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Evaluar </button>
        </div>
    </div>
</form>
<?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-u/YYB4d4WIF5IlXv5bo5F5z5p2YFjz8FEKKZH7oShj5/8G7K8tCrp9lV7bi1b1Hq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
