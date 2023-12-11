<?php
session_start();

include_once('../Config/Conexion.php');

if (isset($_POST['Usuario']) && isset($_POST['Clave']) && isset($_POST['RClave']) && isset($_POST['Rol'])) {
    function validar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = validar($_POST['Usuario']);
    $clave = validar($_POST['Clave']);
    $rClave = validar($_POST['RClave']);
    $rol = validar($_POST['Rol']);

    $usuario_Datos = 'Usuario=' . $usuario;
    
    if (empty($usuario)) {
        header("location: ../Registrarse.php?error=El usuario es requerido&$usuario_Datos");
        exit();
    } elseif (empty($clave)) {
        header("location: ../Registrarse.php?error=La Contraseña es requerida&$usuario_Datos");
        exit();
    } elseif (empty($rClave)) {
        header("location: ../Registrarse.php?error=Repetir la Contraseña es requerida&$usuario_Datos");
        exit();
    } elseif ($clave !== $rClave) {
        header("location: ../Registrarse.php?error=Las Contraseñas no coinciden&$usuario_Datos");
        exit();
    } else {
        $clave = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM usuarios WHERE Usuario = '$usuario'";
        $query = $conexion->query($sql);

        if (mysqli_num_rows($query) > 0) {
            header("location: ../pagina/crearperfil.php?error=El nombre de usuario ya existe&$usuario_Datos");
            exit();
        } else {
            $estado = 1;
            $sql2 = "INSERT INTO usuarios (Usuario, Clave, rol, estado) VALUES ('$usuario','$clave','$rol','$estado')";
            $query2 = $conexion->query($sql2);
         
            if ($query2) {
                header("location: ../pagina/crearperfil.php?success=Usuario creado con éxito!&$usuario_Datos");
                exit();
            } else {
                header("location: ../pagina/crearperfil.php?error=Error desconocido&$usuario_Datos");
                exit();
            }
        }
    }
} else {
    header("location: ../pagina/crearperfil.php");
    exit();
}
?>
