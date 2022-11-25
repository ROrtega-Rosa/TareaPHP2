<?php
// llamamos al archivo para conectar con la base de datos
require_once "config.php";
//variables para el procedimiento almacenado
$operacion="se ha realizado una eliminacion";
    $fech=date("Y-m-d H:i:s");

// si existe un id y es un numero se almacenará en una variable
if(isset($_GET["id"])&& is_numeric($_GET["id"])){
    $id=$_GET["id"];
try{
    // se guarda la consulta en una varible
    $sql="DELETE FROM usuarios WHERE id=:id";
    // se prepara la consulta
    $resulEliminar=$conexion->prepare($sql);
    //se ejecuta
    $resulEliminar->execute(array(":id"=>$id));
    
    // procedimiento almacenado
    $sqlLog = 'CALL insertar_operacion(:fecha,:operacion)';
      $resultadolog = $conexion->prepare($sqlLog);
      $resultadolog->bindparam(':fecha', $fech, PDO::PARAM_STR);
      $resultadolog->bindparam(':operacion', $operacion, PDO::PARAM_STR);
      $resultadolog->execute();
    if($resulEliminar){

        echo '<div class="alert alert-success">'.
        "El registro ha sido eliminado :("." </div>";
        header('Location:listado.php');

    }


}catch(PDOException $ex){


    echo '<div class="alert alert-success">' .
"No pudo eliminar el registro!! :(" .$ex->getMessage(). '</div>';

die();
}



}else{


    echo '<div class="alert alert-success">No existe el id del usuario a eliminar!! :( </div>';
}
