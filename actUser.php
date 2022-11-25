<?php
// llamamos al archivo para conectar con la base de datos
require_once "config.php";
// se crean las variables
$valnombre = "";
$valapellido = "";
$valbiografia = "";
$valemail = "";
$valcontrasenia = "";
$valimagen = "";

// se crean los arrays
$error = [];
$vacio = [];
$correcto = [];
/**
 * @param función a la que se le pasará por parámetros un error y el nombre de un campo
 * @return un alert avisando del error si existe alguno
 */
function errores($error, $campo)
{

  if (isset($error[$campo]) && !empty($campo)) {

    $alert = '<div class="alert alert-danger" role="alert"">'
      . $error[$campo] . '</div>';
  }
  return $alert;
}
/**
 * @param funcion a la se que le pasa por parámetro un array vacío y el nombre de un campo
 * @return una alerta si existe un campo vacío
 */
function vacio($vacio, $campo)
{

  if (isset($vacio[$campo]) && !empty($campo)) {

    $alert2 = '<div class="alert alert-primary" role="alert"">'
      . $vacio[$campo] . '</div>';
  }
  return $alert2;
}
/**
 * @param función a la que se le pasara por parámetro el array correcto y el nombre de un campo
 * @return una variable con los campos correctos que existan.
 */
function correcto($correcto, $campo)
{
  if (isset($correcto[$campo]) && !empty($campo)) {

    $alert3 = '<div>'
      . $correcto[$campo] . '</div>';
  }
  return $alert3;
}
/**
 * @param funcion a la que se le pasará por parámetro el array error y el array vacío
 * @return un mensaje si todo el formulario no tiene errores y no tiene campos vacíos.
 */
function valido($error, $vacio)
{

  if (isset($_POST["actualizar"]) && (count($error) == 0) && (count($vacio) == 0)) {

    $mensaje = '<div class="alert alert-success" style="margin-top:5px;">
        Formulario validado correctamente!! :) </div>';
    return $mensaje;
  }
}
/**
 * si se pulsa el boton actualizar guardamos toda la nueva informacion en nuevas variables
 */
if (isset($_POST["actualizar"])) {

  $id = $_POST["id"];
  $newNombre = trim($_POST["newNombre"]);
  $newApellido = trim($_POST["newApellido"]);
  $newBiografia = $_POST["newBiografia"];
  $newEmail = filter_var($_POST["newEmail"], FILTER_SANITIZE_EMAIL);
  $newContrasenia = $_POST["newContrasenia"];
  $newImagen = "";
  $imagen = null;
  /**
   * tratamos la nueva imagen 
   */
  if (isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])) {


    if (!is_dir("fotos")) {


      $dir = mkdir("fotos", 0777, true);
    } else {

      $dir = true;
    }

    if ($dir) {


      $nombreFich = time() . "-" . $_FILES["imagen"]["name"];
      $moverFich = move_uploaded_file($_FILES["imagen"]["tmp_name"], "fotos/" . $nombreFich);
      $imagen = $nombreFich;
      if ($moverFich) {

        $imagenCargada = true;
      } else {
        $imagenCargada = false;
        $error["imagen"] = "la imagen no se cargo correctamente :(";
      }
    }
  }
  $newImagen = $imagen;
  /**
   * comprobamos que todos los nuevos campos sean correctos
   */
  if (empty($newNombre)) {

    $vacio["newNombre"] = "Debes introducir un nombre" . "</br>";
  } else {

    $formato = preg_match("/^[A-Za-z]{1,20}$/i", $newNombre);
    if ($formato != 1) {

      $error["newNombre"] = "El nombre no es válido :( ";
    } else {
      $correcto["newNombre"] = "El nombre: " . $newNombre . "</br>";
    }
  }

  if (empty($newApellido)) {
    $vacio["newApellido"] = "Debes introducir un apellido" . "</br>";
  } else {
    $patron = preg_match("/^[A-Za-z]+$/i", $newApellido);
    if ($patron != 1) {
      $error["newApellido"] = "El apellido no es válido :(";
    } else {

      $correcto["newApellido"] = "El apellido : " . $newApellido . "</br>";
    }
  }
  if (empty($newBiografia)) {

    $vacio["newBiografia"] = "Debes introducir la biografía" . "</br>";
  } else {


    $correcto["newBiografia"] = "La biografía : " . $newBiografia . "</br>";
  }

  if (empty($newEmail)) {

    $vacio["newEmail"] = "Debes introducir un email" . "</br>";
  } else {
    if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {

      $correcto["newEmail"] = $newEmail;
    } else {
      $error["newEmail"] = "email no valido :(";
    }
  }

  if (empty($newContrasenia)) {

    $vacio["newContrasenia"] = "Debes introducir una contrasenia" . "</br>";
  } else {

    $patron3 = preg_match("/^[a-zA-Z0-9*:-?!¡¡¿]{1,6}$/i", $newContrasenia);
    if ($patron3 != 1) {

      $error["newContrasenia"] = "la contraseña no es valida :(" . "</br>";
    } else {

      $correcto["newContrasenia"] =  sha1($newContrasenia);
    }
  }

  /**
   * si todo el valido entonces se actualiza
   */

  if (valido($error, $vacio)) {
    //variables para el procedimiento almacenado
    $operacion="se ha realizado una actualizacion";
    $fech=date("Y-m-d H:i:s");
    try {
      //se guarda la consulta en una variable
      $sql = "UPDATE usuarios SET nombre= :nombre, apellido= :apellido, biografia= :biografia, email= :email, contrasenia= :contrasenia, imagen= :imagen WHERE id= :id";
      // se prepara la sentencia
      $resultActualizar = $conexion->prepare($sql);
      //se ejecuta la sentencia
      $resultActualizar->execute(array(':id' => $id, ':nombre' => $newNombre, ':apellido' => $newApellido, ':biografia' => $newBiografia, ':email' => $newEmail, ':contrasenia' => $newContrasenia, ':imagen' => $newImagen));
      //procedimiento almacenado
      $sqlLog = 'CALL insertar_operacion(:fecha,:operacion)';
      $resultadolog = $conexion->prepare($sqlLog);
      $resultadolog->bindparam(':fecha', $fech, PDO::PARAM_STR);
      $resultadolog->bindparam(':operacion', $operacion, PDO::PARAM_STR);
      $resultadolog->execute();

      if ($resultActualizar) {
        echo '<div class="alert alert-success">' .
          "Se realizó la actualización !! :)" . '</div>';
      }
    } catch (PDOException $ex) {

      echo '<div class="alert alert-success">' .
        "No se realizó la actualizacion!! :(" . $ex->getMessage() . '</div>';

      die();
    }
  }
  // se introducen los nuevos nombres en las variables creadas anteriormente
  $valnombre = $newNombre;
  $valapellido = $newApellido;
  $valbiografia = $newBiografia;
  $valemail = $newEmail;
  $valcontrasenia = $newContrasenia;
  $valimagen = $newImagen;
  // en el caso de que no se le de al boton aceptar
} else {
  // se comprueba que exista el id y que sea numerico
  if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $id = $_GET["id"]; // se guarda el id en una variable

    try {
      // se guarda la consulta en una variable
      $sql = "SELECT * FROM usuarios WHERE id= :id";
      //se prepara la consulta
      $query = $conexion->prepare($sql);
      //ejecutamos la consulta
      $query->execute(array(':id' => $id));
      if ($query) {
        echo '<div class="alert alert-success">' .
          "Se realizó la consulta !! :)" . '</div>';
        $fila = $query->fetch(PDO::FETCH_ASSOC); // guardamos al consulta en un array 
        //se guarda el array en las variables creadas anteriormente, no los nuevos datos
        $valnombre = $fila["nombre"];
        $valapellido = $fila["apellido"];
        $valbiografia = $fila["biografia"];
        $valemail = $fila["email"];
        $valcontrasenia = $fila["contrasenia"];
        $valimagen = $fila["imagen"];
      }
    } catch (PDOException $ex) {

      echo '<div class="alert alert-success">' .
        "No se realizó la consulta!! :(" . $ex->getMessage() . '</div>';

      die();
    }
  }
}


?>
<!---se crea un html--->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "head.php" ?>
</head>

<body>
  <div class="encabezado">
    <h1>DAWES-PRÁCTICA#3:BASES DE DATOS</h1>
  </div>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-xs-3 col-md-6 col-lg-6">
        <ul>
          <li><a href="formulario.php">Añadir usuario</a></li>
          <li><a href="listado.php">Listar Usuario</a></li>
        </ul>

      </div>
    </div>
  </div>
  <div class="cuerpo text-center">
    <h2>Editar usuario: </h2>
    <div class="container-fluid">

      <!---formulario -->

      <form style="padding: 20px;" enctype="multipart/form-data" method="post">
        <div class="row justify-content-center">

          <label class="col-sm-2 col-form-label">Nombre :</label>

          <div class="col-xs-3 col-md-6 col-lg-6">
            <!--en el atributo value se mostraran las variables creadas anteriormente-->
            <input type="text" class="form-control" placeholder="First name" aria-label="First name" name="newNombre" value=<?php echo $valnombre; ?>></br>
            <!--si se le da al boton actualizar mostrará si hay errores, esta el campo vacío o si es correcto, abriremos un espacio php-->
            <?php if (isset($_POST["actualizar"])) {

              if (!empty($vacio["newNombre"])) {
                echo vacio($vacio, "newNombre");
              } else {
                if (!empty($error["newNombre"])) {
                  echo errores($error, "newNombre");
                } else {
                  echo correcto($correcto, "newNombre");
                }
              }
            }
            ?>

          </div>
        </div>
        <div class="row justify-content-center">
          <label class="col-sm-2 col-form-label">Apellido:</label>
          <div class="col-xs-3 col-md-6 col-lg-6">

            <input type="text" class="form-control" placeholder="Last name" aria-label="Last name" name="newApellido" value=<?php echo $valapellido; ?>></br>
            <?php if (isset($_POST["actualizar"])) {

              if (!empty($vacio["newApellido"])) {
                echo vacio($vacio, "newApellido");
              } else {
                if (!empty($error["newApellido"])) {
                  echo errores($error, "newApellido");
                } else {
                  echo correcto($correcto, "newApellido");
                }
              }
            }
            ?>

          </div>
        </div>
        <div class="row justify-content-center">
          <label class="col-sm-2 col-form-label">Biografia:</label>
          <div class="col-xs-3 col-md-6 col-lg-6">

            <textarea type="text" class="form-control" placeholder="Biografia" aria-label="Biografia" name="newBiografia" value=<?php echo $valbiografia; ?>></textarea></br>
            <?php if (isset($_POST["actualizar"])) {

              if (!empty($vacio["newBiografia"])) {
                echo vacio($vacio, "newBiografia");
              } else {
                if (!empty($error["newBiografia"])) {
                  echo errores($error, "newBiografia");
                } else {
                  echo correcto($correcto, "newBiografia");
                }
              }
            }
            ?>
          </div>
        </div>
        <div class="row justify-content-center">
          <label class="col-sm-2 col-form-label">Email:</label>
          <div class="col-xs-3 col-md-6 col-lg-6">

            <input type="text" class="form-control" placeholder="Email" aria-label="Email" name="newEmail" value=<?php echo $valemail; ?>></br>
            <?php if (isset($_POST["actualizar"])) {

              if (!empty($vacio["newEmail"])) {
                echo vacio($vacio, "newEmail");
              } else {
                if (!empty($error["newEmail"])) {
                  echo errores($error, "newEmail");
                } else {
                  echo correcto($correcto, "newEmail");
                }
              }
            }
            ?>
          </div>
        </div>
        <div class="row justify-content-center">
          <label class="col-sm-2 col-form-label">Contraseña:</label>
          <div class="col-xs-3 col-md-6 col-lg-6">

            <input type="password" class="form-control" placeholder="Contraseña" aria-label="Contraseña" name="newContrasenia" value=<?php echo $valcontrasenia; ?>></br>
            <?php if (isset($_POST["actualizar"])) {

              if (!empty($vacio["newContrasenia"])) {
                echo vacio($vacio, "newContrasenia");
              } else {
                if (!empty($error["newContrasenia"])) {
                  echo errores($error, "newContrasenia");
                } else {
                  echo correcto($correcto, "newContrasenia");
                }
              }
            }
            ?>

          </div>
        </div>
        <div class="row justify-content-center">
          <label class="col-sm-2 col-form-label">Imagen:</label>
          <div class="col-xs-3 col-md-6 col-lg-6">
            <?php if ($valimagen != null) { ?>
              </br>Imagen del Perfil: <img src="fotos/<?php echo $valimagen; ?>" width="60" /></br><?php } ?>
            <input type="file" class="form-control" placeholder="Imagen" aria-label="Imagen" name="imagen"></br>

          </div>
        </div>
        <div class="row justify-content-center">

          <div class="col-xs-3 col-md-6 col-lg-6">

            <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?></br>">

          </div>

          <div class="row justify-content-center">
            <div class="col-xs-3 col-md-6 col-lg-6">

              <input type="submit" class="btn btn-primary" name="actualizar" value="Actualizar">

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