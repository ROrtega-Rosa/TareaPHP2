<!DOCTYPE html>
<html lang="en">

<head>
    <!---Pagina de inicio. Es la primera que se va a encontrar el cliente-->
    <?php require 'head.php' ?> <!--llamada al encabezado-->
</head>

<body>


    <div class="encabezado">
        <h1>DAWES-PRÁCTICA#3:BASES DE DATOS</h1>
    </div>
    <div class="cuerpo text-center">
        <h2> Base de datos de usuarios</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-xs-3 col-md-6 col-lg-6">
            <div class="cuerpo text-center">
                <h3>Bienvenido usuario</h3>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-3 col-md-6 col-lg-6">
                <ul>
                    <!--enlace con el formulario-->
                    <li><a href="formulario.php">Añadir usuario</a></li>
                    <!--enlace con un listado-->
                    <li><a href="listado.php">Listar Usuario</a></li>
                </ul>

            </div>
        </div>
    </div>

    <!---llamamos al footer--->
    <?php require 'footer.php' ?>
</body>

</html>