<?php
  setlocale(LC_ALL, 'es_ES');
  define("HOST", "localhost");     // El alojamiento al que deseas conectarte
  define("USER", "CoffeePay");    // El nombre de usuario de la base de datos
  define("PASSWORD", "@CoffeePay");    // La contraseña de la base de datos
  define("DATABASE", "CoffeePay");    // El nombre de la base de datos
  
  define("CAN_REGISTER", "any");
  define("DEFAULT_ROLE", "member");
  
  define("SECURE", FALSE);    // ¡¡¡SOLO PARA DESARROLLAR!!!!
  
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
  
  if ($mysqli->connect_errno) {
          echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST. "<br> User : ". USER. "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
          die();
  }
  $mysqli->set_charset("UTF8");
    set_time_limit(300);
    ini_set('memory_limit', '-1');

    $JsonFile = 'journal.json';

        if (file_exists($JsonFile)) {
            echo "El fichero $JsonFile existe";
        } else {
            echo "El fichero $JsonFile no existe";
        }
    $json = file_get_contents('journal.json'); 
  
    // Decode the JSON file 
    $json_data = json_decode($json,true); 
    $Counter=0;

        foreach ($json_data as $Row) {
            $Counter+=1;
            $Fecha =$Row['Fecha'];
            $Terminal = $Row['Terminal'];
            $Establecimiento = $Row['Establecimiento'];
            $Operacion =   $Row['Operacion'];
            $Descripcion =  $Row['Descripcion'];
            $Importe = $Row['Importe'];
            $Creditos = $Row['Creditos'];
            $TotalDosisA = $Row['TotalDosisA'];
            $TotalDosisB = $Row['TotalDosisB'];
            $ParcialDosisA = $Row['ParcialDosisA'];
            $ParcialDosisB = $Row['ParcialDosisB'];
            $Caja = $Row['Caja'];       
            //echo ("$Counter, $Fecha,$Terminal, $Establecimiento, $Operacion, $Descripcion, $Importe, $Creditos,$TotalDosisA, $TotalDosisB, $ParcialDosisA, $ParcialDosisB, $Caja <br>");
            
            $Sql2= "insert into journal2 (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe, Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB,Caja) 
                    Values 
                    ('$Fecha','$Terminal', '$Establecimiento', '$Operacion', '$Descripcion', $Importe, $Creditos, $TotalDosisA, $TotalDosisB, $ParcialDosisA, $ParcialDosisB, $Caja)";
    
            //echo $Sql2."<br>";
            $Result2=$mysqli->query($Sql2);
    
        }
        /*
        echo 'ID: ' . $json_data[0]['Id'] . '<br> ';
        echo 'Terminal: ' . $json_data[0]['Fecha'] . '<br> ';
        echo 'Fecha: ' . $json_data[0]['Terminal'] . '<br> ';*/

    echo "Fin  de la operacion. Total Registros : $Counter";



?>