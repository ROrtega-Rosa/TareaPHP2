<?php
// archivo de conexion con la base de datos
$mensaje="";
/**
 * captura de los errores de conexion con la base de datos
 */
try{

    // creamos un objeto PDO con los datos de la conexion
    $conexion=new PDO("mysql:host=localhost;dbname=bdusuarios","root","");
    $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);



    $mensaje= '<div class="alert alert-success">' .
    "Se conectó la base de datos de usuarios!! :)" . '</div>';

}catch(PDOException $ex){


    $mensaje= '<div class="alert alert-danger">' .
    "No se conectó la base de datos usuarios!! :( <br/>". $ex->getMessage(). '</div>'; 
}









?>