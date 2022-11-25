<!DOCTYPE html>
<html lang="en">
<head>
  <!--mediante el espacio php se llama al archivo del encabezado-->
    <?php require "head.php";?> 
</head>
<body>
  <!---ventana modal donde se le pregunta al cliente si realmente desea eliminar el registro-->
<div class="modal fade" id="exampleModal<?php echo $fila["id"];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este registro?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php echo $fila["nombre"];?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <!--si elige esta opción se redirige mediante un enlace al archivo donde tenemos la consulta sql deleter-->
        <button type="button" class="btn btn-primary"><a  href="delUser.php?id=<?php echo $fila['id'];?>" style="color: white; text-decoration:none;">Si</a></button>
      </div>
    </div>
  </div>
</div>
</body>
</html>