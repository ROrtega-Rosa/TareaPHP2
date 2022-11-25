<?php
ob_start();
require_once 'config.php'; //llamamos al archivo de la conexion
$mensaje="";
try{
    //almacenamos la consulta
    $sql="SELECT * FROM usuarios ";
    //preparamos la consulta
    $resultquery=$conexion->query($sql);
   
    
    
    if($resultquery){
        $mensaje= '<div class="alert alert-success">' .
        "Se realizó la consulta !! :)" . '</div>';
        }
    
    }catch(PDOException $ex){
    
    $mensaje= '<div class="alert alert-success">' .
    "No se realizó la consulta!! :(" .$ex->getMessage(). '</div>';
    
    die();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php require "head.php"; ?>
</head>
<body>
<div class="cuerpo text-center">
        <h2> Listar Usuarios</h2>
        
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-3 col-md-6 col-lg-6">
                <!--en una tabla se van a mostrar los datos del usuario-->
                <table class="table table-sniped">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Biografía</th>
                    <th>Email</th>
                    <th>Contrasenia</th>
                    <th>Imagen</th>
                    
                   
                </tr>
                    <?php
                    //se va a recorrer los resultados con un bucle while y un método fetch
                        while($fila=$resultquery->fetch()){

                            echo '<tr>';
                            echo '<td>'.$fila["nombre"].'</td>';
                            echo '<td>'.$fila["apellido"].'</td>';
                            echo '<td>'.$fila["biografia"].'</td>';
                            echo '<td>'.$fila["email"].'</td>';
                            echo '<td>'.sha1($fila["contrasenia"]).'</td>';
                            echo '<td>';
                           if($fila["imagen"]!=null){

                                echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/TareaPHP2/fotos/'.$fila["imagen"].' width="40" /> ' ;
                                
                               
                                
                            }else{

                                echo "----------";
                            }
                           

                        }
                    
                    
                    ?>
                  
                    
                        
                </table>

            </div>
        </div>
    </div>
</body>
</html>
<?php

$html=ob_get_clean();
// llamamos a la librería dompdf
require_once "../TareaPHP2/libreria/dompdf/autoload.inc.php";
//se crea un objeto dompdf
use Dompdf\Dompdf;
$dompdf= new Dompdf();

$opciones=$dompdf->getOptions();
$opciones->set(array('isRemoteEnabled'=>true));
$dompdf->setOptions($opciones);
// introducimos la variable html con el archivo html en otra variable
$dompdf->loadHtml($html);
//elegimos el formato del pdf
$dompdf->setPaper('A4','landscape');
$dompdf->render();
//introducimos el nombre del pdf
$dompdf->stream("archivo_.pdf",array("Attachment"=>false));


?>
