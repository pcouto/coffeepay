<?php
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

        reclog ($key."->".$value . PHP_EOF);

    }

        $json = file_get_contents('php://input');

        reclog($json);

        $data = json_decode($json);

        if (json_last_error() === JSON_ERROR_NONE) {
          reclog ("Json decodificado correctamente");
          }
        else{
            reclog ("Invalid Json");
        }

        $terminal  = $data->{'terminal'};
  
    $Sql = "update datos set  Command = 'DISABLED', CmdValue = '0' where terminal = '$terminal'";

    $Result = mysqli_query($conexion, $Sql);

    $Operacion = "Solicitada Cancelación";
    $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) 
        VALUES ('".date('Y-m-d H:i:s')."','$terminal', '$Establecimiento','$Operacion','',0,'Usuario : $username')";

    $Result = mysqli_query($conexion, $Sql);
?>
