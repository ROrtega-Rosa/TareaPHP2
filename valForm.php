<?php

//espacio php donde se van a recibir los datos y a comprobar si son validos
//arrays donde se va almacenar la informacion
$error=[];
$vacio=[];
$correcto=[];
/**
 * @param función a la que se le pasará por parámetros un error y el nombre de un campo
 * @return un alert avisando del error si existe alguno
 */
function errores($error,$campo){

if(isset($error[$campo])&&!empty($campo)){

    $alert = '<div class="alert alert-danger" role="alert"">'
. $error[$campo] . '</div>';
}
return $alert;

}
/**
 * @param funcion a la se que le pasa por parámetro un array vacío y el nombre de un campo
 * @return una alerta si existe un campo vacío
 */
function vacio($vacio,$campo){

    if(isset($vacio[$campo])&&!empty($campo)){
    
        $alert2 = '<div class="alert alert-primary" role="alert"">'
    . $vacio[$campo] . '</div>';
    }
    return $alert2;
    
    }
    /**
     * @param función a la que se le pasara por parámetro el array correcto y el nombre de un campo
     * @return una variable con los campos correctos que existan.
     */
    function correcto($correcto,$campo){
        if(isset($correcto[$campo])&& !empty($campo)){
    
            $alert3 = '<div>'
        . $correcto[$campo] . '</div>';
        }
        return $alert3;

    }
    /**
     * @param funcion a la que se le pasará por parámetro el array error y el array vacío
     * @return un mensaje si todo el formulario no tiene errores y no tiene campos vacíos.
     */
function valido($error,$vacio){
   
    if(isset($_POST["enviar"]) && (count($error)==0) && (count($vacio)==0)) {
        
        $mensaje='<div class="alert alert-success" style="margin-top:5px;">
        Formulario validado correctamente!! :) </div>';
        return $mensaje;


    }
    }

    /**
     * si existe el boton enviar vamos a recoger los datos con el método post y lo almacenamos en variables
     */
if(isset($_POST["enviar"])){

$nombre=trim($_POST["nombre"]);
$apellido=trim($_POST["apellido"]);
$email= filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
$contrasenia=$_POST["contrasenia"];
$biografia=$_POST["biografia"];
$imagen=null;

/**
 * se comprueba que exisistan los datos almacenados. En caso de que no existan, se almacena
 * en el array de vacío. Por el contrario se comprueba si cumplen las condiciones que se imponen y si no
 * se almacenan en el array de error. si es valido se almacenan en el array correcto
 */


if(empty($nombre)){

$vacio["nombre"]= "Debes introducir un nombre"."</br>";
}else{
    
   $formato=preg_match("/^[A-Za-z]{1,20}$/i",$nombre);
   if($formato!=1){

    $error["nombre"]="El nombre no es válido :( ";

   }else{
    $correcto["nombre"]= "El nombre: ".$nombre."</br>";
   }
}

if(empty($apellido)){
    $vacio["apellido"]="Debes introducir un apellido"."</br>";
}else{
  $patron=preg_match("/^[A-Za-z]+$/i",$apellido);
  if($patron!=1){
    $error["apellido"]= "El apellido no es válido :(";

  }else{

    $correcto["apellido"]= "El apellido : ".$apellido."</br>";
  }

}
if(empty($biografia)){

  $vacio["biografia"]= "Debes introducir la biografía"."</br>";
}else{


    $correcto["biografia"]= "La biografía : ".$biografia."</br>";
}

if(empty($email)){

    $vacio["email"]= "Debes introducir un email"."</br>";
}else{
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){

        $correcto["email"]=$email;
    }else{
        $error["email"]= "email no valido :(";
    }
   
}

if(empty($contrasenia)){

    $vacio["contrasenia"]= "Debes introducir una contrasenia"."</br>";
}else{

    $patron3=preg_match("/^[a-zA-Z0-9*:-?!¡¡¿]{1,6}$/i",$contrasenia);
    if($patron3!=1){

        $error["contrasenia"]= "la contraseña no es valida :("."</br>";
    }else{

        $correcto["contrasenia"]= sha1($contrasenia);
    }
}
/**
 * a las imágenes de les dará un tratamiento especial. Destaca la creacion de un directorio para ir
 * introduciendo dichas imágenes mientras se van recogiendo los datos con el método files.
 */

if(!empty($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])){

    if(!is_dir("fotos")){


        $dir=mkdir("fotos", 0777, true);
    }else{


        $dir=true;
    }

    if($dir){


        $nombreFich=time()."-".$_FILES["imagen"]["name"];
        $moverFich=move_uploaded_file($_FILES["imagen"]["tmp_name"],"fotos/".$nombreFich);
        $imagen=$nombreFich;

        if($moverFich){

            $imagenCargada=true;
        }else{
            $imagenCargada=false;
            $error["imagen"]="la imagen no se cargó correctamente :(";
        }
    }

}

/**
 * si esta todo valido entonces insertamos los datos almacenados en la base de datos
 */

if(valido($error,$vacio)){
    //variables para el procedimiento almacenado
    $operacion="se ha realizado una insercion";
    $fech=date("Y-m-d H:i:s");
    // capturamos los errores de conexion
try{
// almacenamos la consulta
$sql="INSERT INTO usuarios(nombre,apellido,biografia,email,contrasenia, imagen)VALUES(:nombre, :apellido, :biografia, :email, :contrasenia, :imagen)";
//preparamos la conexion con la consulta
$resultInsert=$conexion->prepare($sql);
//se ejecuta la consulta
$resultInsert->execute(array(':nombre'=>$nombre,':apellido'=>$apellido,':biografia'=>$biografia,':email'=>$email,':contrasenia'=>$contrasenia,':imagen'=>$imagen));

//procedimiento almacenado
$sqlLog='CALL insertar_operacion(:fecha,:operacion)';
$resultadolog=$conexion->prepare($sqlLog);
$resultadolog->bindparam(':fecha',$fech,PDO::PARAM_STR);
$resultadolog->bindparam(':operacion',$operacion,PDO::PARAM_STR);
$resultadolog->execute();



}catch(PDOException $ex){


    echo '<div class="alert alert-success">' .
    "No se realizó la consulta!! :(" .$ex->getMessage(). '</div>';
    
    die();
}


}
   




}
