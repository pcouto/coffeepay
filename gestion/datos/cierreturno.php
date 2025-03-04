<?php

    /* 
    ************************************************************************************
        Solicita el cierre de caja realizado desde la gestion de terminales. 
    En la ficha del terminal se guarda en el campo 'command' el valor 'Cierre' con el 
    dato 'importe' que debe corresponder con el importe en la caja del lector, y que 
    se ejecuta  cuando el terminal lanza un 'Alive' al servidor poniendo caja y 
    contadores parciales del terminal a cero.
    ************************************************************************************ 
    */

    include ('../conexion.inc');

    //echo ("Start");
    date_default_timezone_set('Europe/Madrid');

    function reclog ($textToRecord){
        $xml_file = './cierreturno.txt';
        $fh       = fopen( $xml_file, 'a') or die("error creando el fichero");
        fwrite($fh, date("d-m-Y H:i:s")."->");
        fwrite($fh, $textToRecord.PHP_EOL);

    }

    //reclog ("Variables Capturadas :".var_dump($_POST). PHP_EOL);

    //reclog ("Starting");


    foreach ($_POST as $key => $value) {

        //reclog ($key."->".$value . PHP_EOF);

    }

        $json = file_get_contents('php://input');

        //reclog($json);

        $data = json_decode($json);

        if (json_last_error() === JSON_ERROR_NONE) {
          //reclog ("Json decodificado correctamente");
          }
        else{
           // reclog ("Invalid Json");
        }

        $terminal  = $data->{'terminal'};
        $importe = $data->{'importe'};

        //reclog ("Terminal : $terminal");

    $Sql = "update datos set  Command = 'Cierre', CmdValue = '$importe' where terminal = '$terminal'";
    //reclog ($Sql);
    $Result = mysqli_query($conexion, $Sql);
    //reclog ($conexion);
    //reclog ("Result : " .$Result) ;
    $Operacion = "Solicitado Cierre";
    $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) 
        VALUES ('".date('Y-m-d H:i:s')."','$terminal', '$Establecimiento','$Operacion','',$importe,'Usuario : $username')";
    //$Sql = "update datos set  Command = 'Cierre', CmdValue = '$importe' where terminal = '$terminal'";
    $Result = mysqli_query($conexion, $Sql);
?>
