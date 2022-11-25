<?php
// llamada al archivo de conexion con la base de datos
require_once 'config.php';

//paginacion
$pagina=  isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
$postPagina=5;
$inicio= ($pagina>1)? ($pagina * $postPagina - $postPagina) : 0;
// variables para el procedimiento almacenado
    $operacion="se ha realizado un listado";
    $fech=date("Y-m-d H:i:s");
// capturamos los errores de la consulta
try{
//almacenamos la consulta sql en una variable
$sql="SELECT SQL_CALC_FOUND_ROWS * FROM usuarios LIMIT $inicio,$postPagina";
//preparamos la conexion
$resultquery=$conexion->query($sql);
// contamos el numero de registros
$totalUser=$conexion->query("SELECT FOUND_ROWS() as total");
$totalUser=$totalUser->fetch()['total'];
//se redondea el numero de paginas
$numeroPagina= ceil($totalUser / $postPagina);
//procedimiento almacenado
    $sqlLog = 'CALL insertar_operacion(:fecha,:operacion)';
      $resultadolog = $conexion->prepare($sqlLog);
      $resultadolog->bindparam(':fecha', $fech, PDO::PARAM_STR);
      $resultadolog->bindparam(':operacion', $operacion, PDO::PARAM_STR);
      $resultadolog->execute();

if($resultquery){
    echo '<div class="alert alert-success">' .
    "Se realizó la consulta !! :)" . '</div>';
    }

}catch(PDOException $ex){

echo '<div class="alert alert-success">' .
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


<div class="encabezado">
        <h1>DAWES-PRÁCTICA#3:BASES DE DATOS</h1>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-3 col-md-6 col-lg-6">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="generarPdf.php">Generar PDF</a></li>
                    
                </ul>

            </div>
        </div>
    </div>
    <div class="cuerpo text-center">
        <h2> Listar Usuarios</h2>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-3 col-md-12 col-lg-12">
                <!--se introducirá la lista de usuarios en una tabla-->
                <table class="table table-sniped" >
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Biografía</th>
                    <th>Email</th>
                    <th>Contrasenia</th>
                    <th>Imagen</th>
                    <th>Operaciones</th>
                </tr>
                    <?php
                    //se va a recorrer el resultado de la consulta con un bucle y un metodo fetch 
                        while($fila=$resultquery->fetch()){

                            echo '<tr>';
                            echo '<td>'.$fila["nombre"].'</td>';
                            echo '<td>'.$fila["apellido"].'</td>';
                            echo '<td>'.$fila["biografia"].'</td>';
                            echo '<td>'.$fila["email"].'</td>';
                            echo '<td>'.sha1($fila["contrasenia"]).'</td>';
                            echo '<td>';
                            if($fila["imagen"]!=null){

                                echo '<img src="fotos/' . $fila['imagen'] .'" width="40" /> ' . $fila['imagen'];

                            }else{

                                echo "----------";
                            }
                            echo "</td>";
                           
                         /**
                          * botones para eliminar o editar
                          */


                            echo '<td>';
                            
                            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal'.$fila['id'].'" >
                            Eliminar
                          </button>';
                            echo '</td>';

                            echo'<td>';
                            echo '<button type="button" class="btn btn-primary"><a  href="actUser.php?id='. $fila['id'].'" style="color: white; text-decoration:none;">Editar</a></button>';
                            echo '</td>';
 
                            echo '</tr>';
                            //se llamará a la ventana modal
                            require "modalEliminar.php";

                        }
                    
                    
                    ?>

                </table>

            </div>
        </div>
    </div>
   
    
    <!--paginacion--->


    <nav aria-label="Page navigation example">
  <ul class="pagination">

    <?php if ($pagina==1):?> 
        
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
    </li> 
    <?php else: ?>
        <li class="page-item">
      <a class="page-link" href="?pagina=<?php echo $pagina-1?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
    </li> 
    <?php endif ?>

        <?php
        for($i=1;$i<=$numeroPagina;$i++){

            if($pagina==$i){


                echo ' <li class="page-item"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
            }else{

                echo '<li class="page-item"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
            }
        }

    ?>
    <?php if ($pagina==$numeroPagina):?> 
        
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Previous"> <span aria-hidden="true">&raquo;</span></a>
        </li> 
        <?php else: ?>
            <li class="page-item">
          <a class="page-link" href="?pagina=<?php echo $pagina + 1?>" aria-label="Previous"> <span aria-hidden="true">&raquo;</span></a>
        </li> 
        <?php endif ?>
    
  </ul>
</nav>

    <!---llamamos al footer--->
    <?php require 'footer.php' ?>
    


    
</body>
</html>