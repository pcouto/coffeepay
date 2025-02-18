<?php

    date_default_timezone_set('Europe/Madrid');

    include ('conexion.inc');

    function Reclog($StringToRecord){
        $myfile = fopen("catch.txt", "a") or die("Unable to open file!");
        //fwrite($myfile, "--------------------------------------------");
        fwrite($myfile, PHP_EOL);
        fwrite($myfile, $StringToRecord);
        fwrite($myfile, PHP_EOL);
    }

    foreach ($_POST as $key => $value)
       {reclog ($key.'='.$value);}
    reclog ("---------------------");

    $Terminal="";
    if (isset($_POST["terminal"])){ $Terminal = $_POST["terminal"];}
    $Importe = 0;
    if (isset($_POST["importe"])) { $Importe =$_POST["importe"];}
    $Bonos = 0;
    if (isset($_POST["bonos"])) { $Bonos =$_POST["bonos"];}
    $Notas = "";
    if (isset($_POST["notas"]))   { $Notas =$_POST["notas"];}
    $Cmd = "";
    if (isset($_POST["cmd"]))     { $Cmd =$_POST["cmd"];}

    reclog(date("Y-m-d H:m:s"));
    reclog ("Terminal : ".$Terminal);
    reclog("Importe : ".$Importe);
    reclog("Bonos : ".$Bonos);
    reclog("Notas : ".$Notas);
    reclog("Cmd : ".$Cmd);
    $Result = "";
    //$Sql = "update datos set Command = '$cmd', CmdValue='$importe', Notas= '$notas'  where Terminal = '$terminal'";
    if ($Cmd == "cierre"){
      $Sql = "update datos set Command = '$Cmd', CmdValue='$Importe'  where Terminal = '$Terminal'";
      $Result = mysqli_query($conexion, $Sql);
      reclog ($Sql);
    }

    if ($Cmd == "bonos"){
      $Sql = "Select * from datos where terminal = '$Terminal' limit 1";
      $Result = mysqli_query($conexion, $Sql);
      reclog ($Sql);
      if (!$Result){
        reclog ("Error al buscar el terminal");
        die("Error al buscar el numero de terminal");

      }
      $Row = mysqli_fetch_array($Result);
      Reclog("After");
      $Establecimiento = $Row["Establecimiento"];
      $Creditos=$Row["Creditos"];
      $TotalDosisA=$Row["TotalDosisA"];
      $TotalDosisB=$Row["TotalDosisB"];
      $ParcialDosisA=$Row["ParcialDosisA"];
      $ParcialDosisB=$Row["ParcialDosisB"];
      $Caja= $Row["Saldo"];
      
      $Sql = "update datos set Bonos = '$Bonos' where Terminal = '$Terminal'";
      $Result = mysqli_query($conexion, $Sql);
      reclog ($Sql);
      $Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja, notes)
      values
      ('".date('Y-m-d H:i:s')."','$Terminal','$Establecimiento','Bonos Añadidos','$Bonos Bonos desde Disp. Móvil','0.00 €','$Creditos','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja', '$Notas')";
      reclog ($Sql);
      $Result = mysqli_query($conexion, $Sql);      


    }
      if (!$Result){
        reclog("Error al ejecutar el mysql_query : " . $conexion->error);
       }
      else
       {
        reclog ("Sql ejecutado correctemante");
       }     


?>
