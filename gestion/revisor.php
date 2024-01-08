<?php
    // 
    // Buscador de incoherencias en la caja, en las dosis y en los creditos. 
    // 
    include ('conexion.inc');

    $StartFecha = date("Y-m-d",strtotime("20-05-2023"));
    echo $StartFecha;   

    $Sql = "Select * from datos order by 'Terminal' DESC ";
    $TermResult = $conexion->query($Sql);
    
    // Si no hay terminales, rematamos la faena
    if ($conexion->affected_rows==0)
    {
        die("No hay terminales que buscar");
    }

    echo ("<br>Numero de Terminales encontrados : ".$conexion->affected_rows);

    while ( $row = $TermResult->fetch_array(MYSQLI_ASSOC)){
        $Terminal = $row["Terminal"];
        echo ("<br>Analizando Terminal : ". $Terminal. " -> ");     
        $Sql = "Select * from journal where Terminal ='$Terminal' and 'Fecha' >= '$StartFecha' Order By Fecha ASC";
        $MovResult = $conexion->query($Sql);

        echo ("Registros encontrados : ". $conexion->affected_rows);
        $PrevId =0;
        $PrevFecha = "";
        $PrevOperacion="";
        $PrevCreditos = 0;
        $PrevCaja=0;
        $PrevTotalDosisA = 0;
        $PrevTotalDosisB = 0;
        $PrevParcialDosisA = 0;
        $PrevParcialDosisB = 0;
        
        while ( $row = $MovResult->fetch_array(MYSQLI_ASSOC)){
            $Id = $row["Id"];
            $Fecha = $row["Fecha"];
            $Operacion =$row["Operacion"];
            $Creditos= $row["Creditos"];
            $Caja = $row["Caja"];            
            $TotalDosisA = $row["TotalDosisA"];
            $TotalDosisB = $row["TotalDosisB"];            
            $ParcialDosisA = $row["ParcialDosisA"];
            $ParcialDosisB = $row["ParcialDosisB"];

            // Buscamos Diferencias en las dosis A
            If ($TotalDosisA < $PrevTotalDosisA){
                echo ("<tr><br>Error (1) en conteo de TOTAL DOSIS A Terminal :". $Terminal);
                echo ("<br>______________Anotacion Previa Id : $PrevId  TotalDosisA: $PrevTotalDosisA  Fecha : ".date("d-m-Y H:m:s", strtotime($PrevFecha)). " Operacion : ".$PrevOperacion);
                echo ("<br>______________Anotacion Actual Id : $Id      TotalDosisA: $TotalDosisA      Fecha : ".date("d-m-Y H:m:s", strtotime($Fecha)). " Operacion : ".$Operacion);
                echo ("<br>");

            }

            // Buscamos Diferencias en las dosis B
            If ($TotalDosisB < $PrevTotalDosisB){
                echo ("<tr><br>Error (2) en conteo de TOTAL DOSIS B Terminal :". $Terminal);
                echo ("<br>______________Anotacion Previa Id : $PrevId  TotalDosisB: $PrevTotalDosisB  Fecha : ".date("d-m-Y H:m:s", strtotime($PrevFecha)). " Operacion : ".$PrevOperacion);
                echo ("<br>______________Anotacion Actual Id : $Id      TotalDosisB: $TotalDosisB      Fecha : ".date("d-m-Y H:m:s", strtotime($Fecha)). " Operacion : ".$Operacion);
                echo ("<br>");

            }


            // Buscamos diferencias en la caja
            If ($Caja < $PrevCaja){
                echo ("<tr><br>Error (3) en conteo de CAJA Terminal :". $Terminal);
                echo ("<br>______________Anotacion Previa Id : $PrevId  Caja Previa $PrevCaja  Fecha : ".date("d-m-Y H:m:s", strtotime($PrevFecha)). " Operacion : ".$PrevOperacion);
                echo ("<br>______________Anotacion Actual Id : $Id      Caja Previa $Caja      Fecha : ".date("d-m-Y H:m:s", strtotime($Fecha)). " Operacion : ".$Operacion);
                echo ("<br>");

            }

            // Buscamos diferencias en la caja
            if (substr($Operacion,6) == "Cierre"){
                echo ("************ Cierre de caja ->  Fecha : ".date("d-m-Y H:m:s", strtotime($PrevFecha))." Operacion : ".$Operacion);
                $Caja=0;
                $ParcialDosisA=0;
                $ParcialDosisB=0;

            }

            $PrevId = $Id;
            $PrevFecha = $Fecha;
            $PrevOperacion=$Operacion;
            $PrevCreditos = $Creditos;
            $PrevCaja=$Caja;
            $PrevTotalDosisA = $TotalDosisA;
            $PrevTotalDosisB = $TotalDosisB;
            $PrevParcialDosisA = $ParcialDosisA;
            $PrevParcialDosisB = $ParcialDosisB;
 
        }


 
    }

    



    $Sql = "Select * from journal where date >= '".$StartFecha."'";






?>