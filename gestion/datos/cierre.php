<?php
    include ('../conexion.inc');
    
    date_default_timezone_set('Europe/Madrid'); 

    function reclog ($textToRecord){
        $xml_file = './cierre.txt';
        $fh       = fopen( $xml_file, 'a') or die("error creando el fichero");
        fwrite($fh, date("d-m-Y H:i:s")."->");
        fwrite($fh, $textToRecord.PHP_EOL);
       
    }

    reclog ("Variables Capturadas :".var_dump($_POST). PHP_EOL);

    reclog ("Starting");


    foreach ($_POST as $key => $value) {

        reclog ($key."->".$value . PHP_EOF);
 
    }
    
//reclog ("granbando...");

     /* reclog ("Dbname :" );
      reclog ($dbname);
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

        reclog ("Terminal : $terminal");
*/

    if (isset ($_POST["Vterminal"])){
      $terminal = $_POST["Vterminal"];
      reclog ("Terminal definido como : ". $terminal);
       } 
       else
       {
        reclog ("Terminal Fallido");
        //reclog ("Terminal fallido ");
        die();
       }   

    
    reclog "Ha realizado un cierre El terminal pondrá sus contadores a CERO," ;
    reclog  "la próxima vez que se conecte al servidor.";
   
    $Sql = "update datos set  Command = 'Cierre' where terminal = '$terminal'";
    reclog ($Sql);
    
    $Result = mysqli_query($conexion, $Sql);
    //reclog ($conexion);
    //reclog ("Result : " .$Result) ;   

?>