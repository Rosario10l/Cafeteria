<?php
session_start();
include_once('../Config/Conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
        function Validar($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $usuario = Validar($_POST['Usuario']);
        $clave = Validar($_POST['Clave']);

        $error_message = '';

        if (empty($usuario)) {
            $error_message = 'El usuario es requerido';
        } elseif (empty($clave)) {
            $error_message = 'La contraseña es requerida';
        } else {
            $Sql = "SELECT * FROM usuarios WHERE Usuario = '$usuario'";
            $query = mysqli_query($conexion, $Sql);

            if ($query->num_rows == 1) {
                $usuarioQ = $query->fetch_assoc();

                $Nombre = $usuarioQ['Usuario'];
                $Clave = $usuarioQ['Clave'];
                $estado = $usuarioQ['estado'];
                $rol = $usuarioQ['rol']; 

                if ($estado === '0') {
                    $error_message = 'Tu cuenta ha sido desactivada. Por favor, contacta al administrador.';
                } else {
                    if ($usuario === $Nombre) {
                        if (password_verify($clave, $Clave)) {
                            $_SESSION['Usuario'] = $Nombre;
                            $_SESSION['rol'] = $rol;

                            echo "<script>
                                location.href = '../pagina/inicio.php';
                            </script>";
                            exit();
                        } else {
                            $error_message = 'Usuario o Contraseña incorrecta';
                        }
                    } else {
                        $error_message = 'Usuario o Contraseña incorrecta';
                    }
                }
            } else {
                $error_message = 'Usuario o Contraseña incorrecta';
            }
        }

        if (!empty($error_message)) {
            header('Location: ../index.php?error=' . urlencode($error_message));
            exit();
        }
    }
}
?>
