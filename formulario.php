<!DOCTYPE html>
<html lang="en">

<head>
  <!---en el espacio php llamamos al archivo con el encabezado del html-->
    <?php require 'head.php' ?> 
    
</head>

<body>
<!--Llamamos al archivo de la conexion-->
<?php require "config.php" ?>
<div class="encabezado">
  <h1>DAWES-PRÁCTICA#3:BASES DE DATOS</h1>
</div>
<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-3 col-md-6 col-lg-6">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    
                </ul>

            </div>
        </div>
    </div>
<div class="cuerpo text-center">
  <h2>Añadir usuario: </h2>
  <div class="container-fluid">
    <!---formulario -->

    <?php require 'valForm.php' ?><!--llamamos al archivo donde se valida el formulario-->
    <!--en el caso de que este todo validado correctamente se muestra el mensaje que retorna la funcion valido-->
    <?php echo valido($error,$vacio);?> 
    <!--en el formulario introduciremos el atributo enctype para tratar imagenes, el metodo post,
    y en action la funcion htmlspecialchars para conectar el archivo de formulario.php con valform.php-->
    <form style="padding: 20px;" enctype="multipart/form-data" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
<div class="row justify-content-center">

      <label class="col-sm-2 col-form-label">Nombre :</label>

  <div class="col-xs-3 col-md-6 col-lg-6">
    
    <input type="text" class="form-control" placeholder="First name" aria-label="First name" name="nombre"></br>
    <!--para mostrar si hay errores, esta el campo vacío o si es correcto, abriremos un espacio php-->
    <?php  if( isset($_POST["enviar"]) ){

    if(!empty($vacio["nombre"])){
        echo vacio($vacio,"nombre");

      }else{
        if(!empty($error["nombre"])){
          echo errores($error,"nombre");

        }
         else{
          echo correcto($correcto,"nombre");

        }
      }
    }
    ?>
    
  </div>
</div>
<div class="row justify-content-center">
<label class="col-sm-2 col-form-label">Apellido:</label>
  <div class="col-xs-3 col-md-6 col-lg-6">
    
    <input type="text" class="form-control" placeholder="Last name" aria-label="Last name" name="apellido"></br>
    <?php  if( isset($_POST["enviar"]) ){

if(!empty($vacio["apellido"])){
    echo vacio($vacio,"apellido");

  }else{
    if(!empty($error["apellido"])){
      echo errores($error,"apellido");

    }
     else{
      echo correcto($correcto,"apellido");

    }
  }
}
?>
  </div>
</div>
<div class="row justify-content-center">
<label class="col-sm-2 col-form-label">Biografia:</label>
  <div class="col-xs-3 col-md-6 col-lg-6">
    
    <textarea type="text" class="form-control" placeholder="Biografia" aria-label="Biografia"name="biografia"></textarea></br>
    <?php  if( isset($_POST["enviar"]) ){

if(!empty($vacio["biografia"])){
    echo vacio($vacio,"biografia");

  }else{
    if(!empty($error["biografia"])){
      echo errores($error,"biografia");

    }
     else{
      echo correcto($correcto,"biografia");

    }
  }
}
?>
  </div>
</div>
<div class="row justify-content-center">
<label class="col-sm-2 col-form-label">Email:</label>
  <div class="col-xs-3 col-md-6 col-lg-6">
    
    <input type="text" class="form-control" placeholder="Email" aria-label="Email" name="email" ></br>
    <?php  if( isset($_POST["enviar"]) ){

if(!empty($vacio["email"])){
    echo vacio($vacio,"email");

  }else{
    if(!empty($error["email"])){
      echo errores($error,"email");

    }
     else{
      echo correcto($correcto,"email");

    }
  }
}
?>
    </div>  
</div>
<div class="row justify-content-center">
<label class="col-sm-2 col-form-label">Contraseña:</label>
  <div class="col-xs-3 col-md-6 col-lg-6">
    
    <input type="password" class="form-control" placeholder="Contraseña" aria-label="Contraseña" name="contrasenia"></br>
    <?php  if( isset($_POST["enviar"]) ){

if(!empty($vacio["contrasenia"])){
    echo vacio($vacio,"contrasenia");

  }else{
    if(!empty($error["contrasenia"])){
      echo errores($error,"contrasenia");

    }
     else{
      echo correcto($correcto,"contrasenia");

    }
  }
}
?>
  </div>
</div>
<div class="row justify-content-center">
<label class="col-sm-2 col-form-label">Imagen:</label>
  <div class="col-xs-3 col-md-6 col-lg-6">
 
    <input type="file" class="form-control" placeholder="Imagen" aria-label="Imagen" name="imagen"></br>

  </div>
</div>
<div class="row justify-content-center">
<div class="col-xs-3 col-md-6 col-lg-6">
    <!--boton para enviar los datos-->
    <input type="submit"class="btn btn-primary" name="enviar" value="submit">
    
  </div>
</div>
</div>
</form>

</div>
</div>
    
    <!---llamamos al footer--->
    <?php require 'footer.php' ?>

</body>

</html>