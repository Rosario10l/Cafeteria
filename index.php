<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAFETERIA</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body style="background-color: rgb(249, 191, 162);">
<br><br><br><br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mx-auto my-auto login-form">
                    <br>
                    <img style="height: auto; width: 13%;margin-top: auto; margin-bottom: 0%; margin-left: auto; margin-right: auto; display: block;"
                        alt="Logo"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAAIMklEQVR4nO1aa1BV1xX+zgUuF0Tw+gBUQF5BXqIoiqCgFRFUgpKADwS1SDNqmhp1rJQMjpIxrXmZviYz6XRMUieZJpjUauMzNUqazGQmttPHDzW2samZBmKUmGLaar/O2nefywF5qdcB5H4za7jn7LUf69trr7X3PgBeeOGFF1544YUXnkMggHoAHwG4DoAWacQgMP79DkZb5RTucdRrQz8GkAfAjkGGjzQBYvygg79e8//t5cz/TpP1JYA3AYzHAEe+NuhML/UbO8SHLwBEYgDCDmAegAvakLpOZrq76B8B4C1d9zUM8FT3LgBHB12zrDtEap0WDMBUdx3AWQDbOjH+Vgm4gnss1eVo3Ys9GH9I6/0S90CqswPYBeBTy+x/vxOjG3QGMHU+1/Gg3+O6HnBXM/8Di1EX9bO9g/GXLDoteuYHhPG9WdMXdXl2F+UNuvwggLEYgGAPBPRUbrr9gJlxTxPQm6zQr9HYw6nuTsu98MILL/o1bAAmAKjWOzzZxLwD4I8A/qGPtV/oewFa9vjy7hMA5wH8BcBxAHsAbAfwzYFwH+AE8AyApm7u/O5UhMCdAILRD3HaHGiQHRwXYnBymMFZUQYXxhtcmmRjZaqN1RNtXJveuUiZ6Iiu1JG60oa0JW12QshXut/HAYT2NQG0EhAVbDA9zGBupMEFcQaXJNlYkWJjVVrXBEiZ6Iiu1JG60oa01QUBVpEdZGmfE+AM8rtb7k/nULv7d1NDAS8dr+DRHxdy4YxI8/3/+pIEinz2egEbd8/gnroc1q1JZ+WCeOZnjuXU5JGMiwjm8GB/ZUiAv4/bGPkt76RMdERX6khdaWPPtlye/sViXmtc3Y6QsOEBXF4Qx9ZTq7jjocnW0+OoPiOgqaFAydfvrvao3PjyHG+0nL3JK/x8bf9pOrLi36IzPzvCfF9/zxFgosNhKUyOzF83rt4qOod/WGCWnR4sBCjY/WwfZKeFsenICmtAHDQERMpzSJDdSkBLnxNwrXGlZ2NAy1neaDnTkQD3hWnpnGgeeq4fLYHWk5Ue9wKRztKjZI/zb5SxMMsdBHf0OQFXjy+96wQED/FTMy/Gb6tOt54rRuoxzQZwWC+JzwD8GkDG3SLgmgzgb3vnKgIuH1zkceObj7rWuN3XxuZ9hepZ3N4y87IRekCPZ6t+7ugx8m793SDggnTw3o9murxgXyFb36nwKAF/2FvS3U7xisV42Q3SMFybLPm9eUUqa1al0dfHMEko9jQB+6WjZ9amuJeBp73gJ1uyOhp9FcCHes2bbu8wP7pseCCW/n4+9LEZ/Hi/a0n+9LvZZt3z+pO9x7BWGs6ZMMJNgEjLkVKPEZCbHm4OfmU341glOsnjhvLQE5lKPzlmmKrfcqKSL2+fRYe9bRsOoFV/xmvQ7QbcLgFOQ9/p/6p+ahsJ+4SEsjs23rLL+7yLj6wm3hC93etSeHSXy2MSokK4/+l8jhkV2JtD10XLUuoUIwCkAZgLoBzABn0ef8H86jMuLIBnX5rTzhMuH1h02zHh07eWM3p0kPVS5OcAngRQo2+eFgJI1Rcl4to8/Xwu//7KXBUDJBaIyPvU2GHcvTGTH7xYzH8eLueV31by3L4yPr81mxPinNZAuclq8GadTnp92zMtcRjPvNieBAmMEheuHluqyOhusyQnP9lLXNhXwqmJw2/56PzJq/mqzzVFcerZZhisLb+PrSdv7vPy25WKkMYXilSg1GTdADATJqOm+NpApwOMDDaYOMLgpDCDmWNclxf50QbnxxoMcahIy5jwQDZsy2hPwi3Ia3UZypvUVtcB1bb0IX1Jn5NCDY4fbnDsUIMh/qCP7WYC8jPC1POWJfGu/cmxpe0Mf7gsSe0nzHoSHyxH9YPQ107tCJDOxgSBCcMNTgx1DSYn0mCeJmBejMEA37Y6WclOlR1OPJ2t3LIrg2XQoiO605Pc7qjayo8xWBhrcM44gzMiDE4dbTBNEyDP6lZpih9DHK7Bv6/TccgQX/X8yOIY/ulns9n85gJeO1XJD19exFTt8jLjKbFOpieMUBnDYu8lIeA9eQgcEsSgocE0jHYK/UIc/v6s+V4ta+t3MTFlgnr37DpXOrbeUIlxkh2Sooa6Y4Ks+9/vXez2CkmXsmewElCtZiFwCBeXV7H8oe+wZEUVl62sZvGDy5hXeD+n58xmesZ0pk6czKTUNMbEJzA6Np7hYyIYGj6aw5wjFHmOgADabLYuDRFyRUckZJhT1QsNG63aiYiKZux945mSls4p07I5Y1Ye8wqLuLBkCb/17c2sffwpJQX3uzZL6fEh6nZq3pRRbtf292vrOzjQl+sXx7HpN6VsPSnxSOLOShWbtlYkm3oHhAAffUdPP7udyZMyOLuwmGUrq7mxdoe7476STY/Vs/rhjSwtX8Vp2bm029vuDXdVJ6mUbGaBjQ/G8tiTWTz5bLY7RlhFCKtZFq8CpmEJgoIgAK93Nmv+/g6OCgtXszMhPYNZOd/g3PnFLC5dziUVVaxYs45V6zZw/aYaPlqznZtq65VsqdvpNkJ+m+9FR3SljtSVNqQtaVPalj7iEhIZGj6GDocrQHYQSWEn5K+4vAS/9cXR7vLEqCAVY8w0ee6lPL79VBZ3ViWqMksbj6ITZOp/aRHX+HPHANlHclWP5YD+aJJhOQzduI32ZJ9RglvASABTABTpT1iPAXgOwCs6jZzUFxSSTpstn8as5H1led+sdU/rugd1W9Jmre6jSPdp7v+7wkz9b7bNOqBJW48AeFX3If3+C8Bf9T9hVvSwy/TCCy+88MILLzB48H9jHENa3PE/cAAAAABJRU5ErkJggg==">
                    <h1 class="text-center">Cafeteria</h1>
                    <form style="margin: 20px auto; margin-top: 1px; max-width: 300px;" method="POST" action="php/login.php">
                        <?php if (isset($_GET['error'])) { ?>
                            <p class="error-message">
                                <?php echo $_GET['error'] ?>
                            </p>
                        <?php } ?>
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="usuario" class="form-label">Nombre de Usuario:</label>
                            <input type="text" id="usuario" class="form-control" name="Usuario" required style="margin: 0 auto;">
                        </div>
                
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="contraseña" class="form-label">Contraseña:</label>
                            <input type="password" id="contraseña" class="form-control" name="Clave" required style="margin: 0 auto;">
                        </div>
                
                        <button type="submit" class="btn btn-primary form-control btn-block">Enviar</button>
                    </form>
                </div>                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</body>

</html> 