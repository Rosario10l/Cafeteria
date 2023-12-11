<?php

session_start();
if (isset($_SESSION['rol']) && $_SESSION['rol'] == 2) {

    include('../Config/Conexion.php');

 
    $queryUpdateDenominaciones = "UPDATE caja SET cantidad = 0";
    mysqli_query($conexion, $queryUpdateDenominaciones);
}


$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al formulario de inicio de sesión o a la página de inicio
header('Location: ../index.php'); // Cambia 'login.php' por la página a la que deseas redirigir al cerrar sesión
exit();

// Comprobar si el rol es igual a 2

?>
