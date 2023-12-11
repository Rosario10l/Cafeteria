<?php
session_start();
include_once('../Config/Conexion.php');

if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header('Location: ../index.php');
    exit();
}

$nombrePerfil = $_SESSION['Usuario'];
$rol = $_SESSION['rol'];
$denominacionesCero = isset($_SESSION['denominaciones_cero']) ? $_SESSION['denominaciones_cero'] : false;
$cajaAbierta = isset($_SESSION['caja_abierta']) ? $_SESSION['caja_abierta'] : false;

if ($rol == 1) {
    $nombreRol = 'Administrador';
} elseif ($rol == 2) {
    $nombreRol = 'Cajero';
} elseif ($rol == 3) {
    $nombreRol = 'Barista';
    $sqlIngredientesBajos = "SELECT COUNT(*) AS total_ingredientes FROM almacen WHERE cantidad_almacen <= 10";
    $resultIngredientesBajos = $conexion->query($sqlIngredientesBajos);
    $filaIngredientesBajos = $resultIngredientesBajos->fetch_assoc();
    $totalIngredientesBajos = $filaIngredientesBajos['total_ingredientes'];

    if ($totalIngredientesBajos > 0) {
        echo "<script>
                toastr.warning('Hay pocos ingredientes. Revisa el inventario.');
              </script>";
    }
}

$sqlCaja = "SELECT * FROM caja";
$resultCaja = $conexion->query($sqlCaja);

// Inicializa el total del dinero en la caja
$dineroTotal = 0;

while ($filaCaja = $resultCaja->fetch_assoc()) {
    $denominacion = $filaCaja['denominacion'];
    $cantidad = $filaCaja['cantidad'];

    // Calcula el valor total para esta denominación y suma al total general
    $valorDenominacion = $denominacion * $cantidad;
    $dineroTotal += $valorDenominacion;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <style>
    .card-link {
        text-decoration: none; 
    }
</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <h1 class="nav-link" href="#">Cafetería</h1>
                    </li>
                </ul>
            </div>
            <div class="navbar-text ms-auto">
                <span class="text-muted"><?php echo $nombreRol; ?></span>
                <span class="fw-bold mx-2"><?php echo $nombrePerfil; ?></span>
                <a class="btn btn-outline-warning" href="../php/cerrarsesion.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if ($rol == 1) { ?>
                <div class="col-md-4 mb-4">
                <a href="reportedeventas.php" class="card-link  link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sacar Reporte de Ventas</h5>
                            
                            <img src="" class="card-img-top" alt="Descripción de la imagen">
                            <p class="card-text">Descripción de cómo sacar reportes de ventas.</p>
                        </div>
                    </div>
                </a>
                </div>
                <div class="col-md-4 mb-4">
                <a href="crearperfil.php" class="card-link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Crear Perfil</h5>
                            <img src="" class="card-img-top" alt="Descripción de la imagen">
                            <p class="card-text">Descripción de cómo crear un perfil.</p>
                        </div>
                    </div>
                </a>
                </div>
                <?php } elseif ($rol == 2) {  ?>
                    <div class="col-md-4 mb-4">
                    <a href="<?php echo $cajaAbierta ? '#' : 'abrircaja.php'; ?>" class="card-link">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Abrir Caja</h5>
                                <img src="" class="card-img-top" alt="Descripción de la imagen">
                                <?php if ($denominacionesCero) { ?>
                                    <p class="card-text">Las denominaciones son cero. No puedes realizar esta acción.</p>
                                <?php } elseif ($cajaAbierta) { ?>
                                    <p class="card-text">La caja ya está abierta.</p>
                                <?php } else { ?>
                                    <p class="card-text">Para que puedas abrir caja es necesario ingresar el monto, despues de esto sera necesario que desgloses las denominaciones</p>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a <?php echo $denominacionesCero || !$cajaAbierta ? 'data-bs-toggle="modal" data-bs-target="#mensajeModal"' : 'href="cobrarproducto.php"'; ?> class="card-link">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Cobrar producto</h5>
                                <img src="" class="card-img-top" alt="Descripción de la imagen">
                                <?php if ($denominacionesCero || !$cajaAbierta) { ?>
                                    <p class="card-text">No puedes cobrar productos en este momento.</p>
                                <?php } else { ?>
                                    <p class="card-text">Descripción de cómo agregar cambio.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a <?php echo $denominacionesCero || !$cajaAbierta ? 'data-bs-toggle="modal" data-bs-target="#mensajeModal"' : 'href="agregarcambio.php"'; ?> class="card-link">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Agregar Cambio</h5>
                                <img src="" class="card-img-top" alt="Descripción de la imagen">
                                <?php if ($denominacionesCero || !$cajaAbierta) { ?>
                                    <p class="card-text">No puedes agregar cambio en este momento.</p>
                                <?php } else { ?>
                                    <p class="card-text">Descripción de cómo agregar cambio.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } elseif ($rol == 3) { ?>
                <div class="col-md-4 mb-4">
                <a href="ingresarinsumos.php" class="card-link link">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ingresar insumos</h5>
                            <img src="" class="card-img-top" alt="Descripción de la imagen">
                            <p class="card-text">Ingresa los insumos cuando no haya.</p>
                        </div>
                    </div>
                </a>
                </div>
                <div class="col-md-4 mb-4">
                <a href="crearcafe.php" class="card-link ">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Crear Cafe</h5>
                            <img src="" class="card-img-top" alt="Descripción de la imagen">
                            <p class="card-text">Crear cafes para poder venderlos</p>
                        </div>
                    </div>
                </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
