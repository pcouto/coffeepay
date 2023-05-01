<?php

    include "./conexion.inc";


    $sql ="Select * from datos";


    $result = mysqli_query($conexion,$sql);

    if (mysqli_errno($conexion)){
      echo "Error ". mysqli_errno($conexion). "<br>".mysqli_error($conexion);
      die();
    }


    if(mysqli_connect_errno()){
      reclog("Error ->".mysql_errno($conexion)." ".mysql_error($conexion));
      echo "No hay conexion con la bd";
      die();
    }



    if (mysqli_affected_rows($conexion)==0) {
      // El terminal no existe en la base de datos
      echo "No hay terminales definidas";
      die();
    }


    $row = mysqli_fetch_array($result);
    $terminal = $row["terminal"];
    $saldoterminal = $row["saldo"];
    $activo = $row["activo"];

    echo "Encontrado al menos un terminal ($terminal) con saldo :".$saldoterminal ." y estado : ". $activo



 ?>
