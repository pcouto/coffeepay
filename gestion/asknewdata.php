<?php

        $Version = "AsknewData  1.0 27/07/2023";
        date_default_timezone_set('Europe/Madrid');

        //include ('conexion.inc');

        $servidor = 'localhost';
        $usuario = 'CoffeePay';
        $contrasena = '@CoffeePay';

        $dbname = "BackupCoffeepay";
        $conexion = mysqli_connect($servidor, $usuario, $contrasena, $dbname) ;

        // Si hay errores , no se continua con ellos
        /* Comprueba la conexión */
        if (mysqli_connect_errno()) {
            echo("Fallo de conexion: %s\n". mysqli_connect_error());
            exit();
        }
        mysqli_set_charset($conexion, "utf8");


        $Sql = "Select * from journal order by id desc limit 1";
        $result = mysqli_query ($conexion,$Sql);  
        if (!$result) {
            die('<br>Invalid query: ' . mysqli_error($conexion));
        }

        $row = mysqli_fetch_assoc($result);
        if (mysqli_affected_rows($conexion) ==0){
            $Id = 0;
        }
        else {
            $Id = $row['Id'];
        }
        echo ("ID:".$Id);
      $encrypt_method = "AES-128-ECB";
      $key = 'CoFfeePay';

      $firma = base64_encode(date('Y-m-d H:i:s'));
      $firma = base64_encode($firma);
      $firma = openssl_encrypt($firma, $encrypt_method, $key);

         //echo ("Total Filas nuevas : ".mysqli_affected_rows($conexion));

        //$url = curl_init("http://localhost/coffeepay/gestion/catchdata.php?Id=".$Id."&Firma=".$firma);
        $url= curl_init("https://knessen.com/Duetazze/gestion/catchdata.php?Id=".$Id."&Firma=".$firma);      
         curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
        $JsonString = curl_exec($url);
      //echo  $JsonString;
        
        if(curl_error($url)) {
           echo (curl_error($url)); 
           die();
        }
        curl_close($url);
    
        $decoded_json = json_decode($JsonString,false);
        
        if (!$decoded_json){
            die ("No data to add");
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
               // echo ' - No errors';
            break;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            default:
                echo ' - Unknown error';
            break;
        }


               
        $counter = 0;
        foreach ($decoded_json as $objeto) {

            $counter++;
            $Id= $objeto->Id;
            $Fecha= $objeto->Fecha;
            $Terminal= $objeto->Terminal;
            $Establecimiento= $objeto->Establecimiento;
            $Operacion= $objeto->Operacion;
            $Descripcion= $objeto->Descripcion;
            $Importe= $objeto->Importe;
            $Creditos= $objeto->Creditos;
            $TotalDosisA= $objeto->TotalDosisA;
            $TotalDosisB= $objeto->TotalDosisB;
            $ParcialDosisA= $objeto->ParcialDosisA;
            $ParcialDosisB= $objeto->ParcialDosisB;
            $Caja= $objeto->Caja;
            $Notes= $objeto->Notes;

            $Sql = "insert into journal (Id, Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe, Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja, Notes)
                    values 
                    ('$Id', '".date($Fecha)."', '$Terminal', '$Establecimiento', '$Operacion', '$Descripcion', '$Importe', '$Creditos', '$TotalDosisA', '$TotalDosisB', '$ParcialDosisA', 
                    '$ParcialDosisB', '$Caja', '$Notes') ";

                    $result = mysqli_query ($conexion,$Sql);  

             echo "<br>Contador".$counter. " -> Id ".$Id. " Fecha : $Fecha Terminal : $Terminal";

            if (!$result) { 
                echo('<br>Invalid query: ' . mysqli_error($conexion));
                echo "<br>". $Sql;
                die();
            }

        }

  

    
        echo "------".PHP_EOL;


 ?>