<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            background-color: #007bff;
            color: #fff;
        }
        .error {
    background-color: #000000;
    text-align: center;
   }
   .success{
    text-align: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                       <a href="inicio.php" class="back-btn text-decoration-none">&lt; Volver</a>
                        <h4 class="card-title text-center mb-4">Crear Perfil</h4>
                        <?php
                            if (isset($_GET['error'])) {
                                echo '<div class="alert alert-danger">' . $_GET['error'] . '</div>';
                            } elseif (isset($_GET['success'])) {
                                echo '<div class="alert alert-success">' . $_GET['success'] . '</div>';
                            }
                        ?>
                        <form action="../php/Registrar.php" method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Nombre de Usuario:</label>
                                <input type="text" class="form-control" id="usuario" name="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="clave" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="clave" name="Clave" required>
                            </div>
                            <div class="mb-3">
                                <label for="rclave" class="form-label">Repetir Contraseña:</label>
                                <input type="password" class="form-control" id="rclave" name="RClave" required>
                            </div>
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol:</label>
                                <select class="form-control" id="rol" name="Rol" required>
                                    <option value="">Selecciona un Rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Cajero</option>
                                    <option value="3">Barista</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-submit">Crear Perfil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
