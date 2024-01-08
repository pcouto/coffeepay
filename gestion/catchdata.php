<?php

/* Este script devuelve los movimientos de la base de datos  desde la id indicada en adelante */

    $Version = "1.0 26/07/23";

    date_default_timezone_set('Europe/Madrid');

    include ('conexion.inc');


    $Id = "";
    if (isset ($_GET['Id']))
    {
        $Id = $_GET['Id'];

    }

    if ($Id == ""){
        die("Error inexperado");
    }

    $Firma = "";
    if (isset ($_GET['Firma']))
    {
        $Firma = $_GET['Firma'];
    }
    else     
    {
        die("error inexperado o ...");
    }

    $Firma = str_replace(" ", "+",$Firma);
    $encrypt_method = "AES-128-ECB";    
    $key = 'CoFfeePay';

    $decrypted = openssl_decrypt($Firma, $encrypt_method, $key);
    $decrypted = base64_decode($decrypted);
    $decrypted = base64_decode($decrypted);

    if (date('Y-m-d') <> date('Y-m-d',strtotime($decrypted))){
          
        echo date('Y-m-d')."----->".date('Y-m-d',strtotime($decrypted));
        die("error en firma");
    }


    $Sql = "Select * from journal where Id > $Id";
   // echo "<br>".$Sql."<br>";
    $result = mysqli_query ($conexion,$Sql);  
    if (!$result) {
        die('<br>Invalid query: ' . mysqli_error($conexion));
    }
    
    $TotalRegs =  mysqli_affected_rows($conexion);
   
    $Counter =0;
    while ($row = mysqli_fetch_assoc($result))
        {  
            $Counter = $Counter +1;
            $Start = false;
            $Id= $row["Id"];
            $Fecha= $row["Fecha"];
            $Terminal= $row["Terminal"];
            $Establecimiento= $row["Establecimiento"];
            $Operacion= $row["Operacion"];
            $Descripcion= $row["Descripcion"];
            $Importe= $row["Importe"];
            $Creditos= $row["Creditos"];
            $TotalDosisA= $row["TotalDosisA"];
            $TotalDosisB= $row["TotalDosisB"];
            $ParcialDosisA= $row["ParcialDosisA"];
            $ParcialDosisB= $row["ParcialDosisB"];
            $Caja= $row["Caja"];
            $Notes= $row["Notes"];

            $Movimientos[] = array('Id'=> $Id, 'Fecha'=> $Fecha, 'Terminal'=> $Terminal, 'Establecimiento'=> $Establecimiento,'Operacion'=> $Operacion,
            'Descripcion'=> $Descripcion,'Importe'=> $Importe ,'Creditos'=> $Creditos, 'TotalDosisA'=> $TotalDosisA, 'TotalDosisB'=> $TotalDosisB,'ParcialDosisA'=> $ParcialDosisA,
            'ParcialDosisB'=> $ParcialDosisB, 'Caja'=> $Caja, 'Notes'=> $Notes);

        }

        //desconectamos la base de datos
        $close = mysqli_close($conexion) ;
        //die("Ha sucedido un error inexperado en la desconexion de la base de datos");

        //Creamos el JSON
        $json_string = json_encode($Movimientos);
        echo $json_string;

?>