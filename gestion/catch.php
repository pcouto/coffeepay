<?php

    date_default_timezone_set('Europe/Madrid');

    include ('conexion.inc');

    function Reclog($StringToRecord){
        $myfile = fopen("catch.txt", "a") or die("Unable to open file!");
        $txt = "nueva llamada";
        fwrite($myfile, $StringToRecord);
        fwrite($myfile, PHP_EOL);
    }

    foreach ($_POST as $key => $value)
        reclog ($key.'='.$value.'<br />');


    $terminal="";
    if (isset($_POST["terminal"])){ $terminal = $_POST["terminal"];}
    $importe = 0;
    if (isset($_POST["importe"])) { $importe =$_POST["importe"];}
    $notas = "";
    if (isset($_POST["notas"]))   { $notas =$_POST["notas"];}
    $cmd = "";
    if (isset($_POST["cmd"]))     { $cmd =$_POST["cmd"];}

    reclog(date("Y-m-d H:m:s"));
    reclog ("Terminal : ".$terminal);
    reclog("Importe : ".$importe);
    reclog("Notas : ".$notas);
    reclog("Cmd : ".$cmd);

   
    //$Sql = "update datos set Command = '$cmd', CmdValue='$importe', Notas= '$notas'  where Terminal = '$terminal'";
    $Sql = "update datos set Command = '$cmd', CmdValue='$importe'  where Terminal = '$terminal'";
     $Result = mysqli_query($conexion, $Sql);
    reclog ($Sql);

    if (!$Result){
        reclog("Error al ejecutar el my sql_query : " . $conexion->error);
       }
    else
       {
        reclog ("Sql ejecutado correctemante");
       }

?>