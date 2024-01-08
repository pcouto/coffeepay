<?php
    include ('../conexion.inc');
    
    date_default_timezone_set('Europe/Madrid'); 

    function reclog ($textToRecord){
      $xml_file = './resetterm.txt';
      $fh       = fopen( $xml_file, 'a') or die("error creando el fichero");
      fwrite($fh, date("d-m-Y H:i:s")."->");
      fwrite($fh, $textToRecord.PHP_EOL);
     
  }

  $postdata = file_get_contents("php://input");

   reclog ("Comenzando resetterm");
   reclog(var_dump($_POST));
    

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

        //reclog ("Terminal : $terminal");



    echo "El terminal pondrá sus contadores a CERO," ;
    echo  PHP_EOL;
    echo  PHP_EOL;
    echo  "la próxima vez que se conecte al servidor.";
   
    $Sql = "update datos set  Command = 'Reset' where terminal = '$terminal'";
    //reclog ($Sql);
    $Result = mysqli_query($conexion, $Sql);

    //reclog ("Result : " .$Result) ;   
    $Operacion = "Solicitado Reset";
    $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) 
        VALUES ('".date('Y-m-d H:i:s')."','$terminal', '$Establecimiento','$Operacion','$importe ',0,'Usuario : $username')";
    //$Sql = "update datos set  Command = 'Cierre', CmdValue = '$importe' where terminal = '$terminal'";
    $Result = mysqli_query($conexion, $Sql);




?>