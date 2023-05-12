  <?php

      date_default_timezone_set('Europe/Madrid');

      include ('conexion.inc');


      function Reclog($StringToRecord){
        $myfile = fopen("Alive.txt", "a") or die("Unable to open file!");
        $txt = "nueva llamada";
        fwrite($myfile, $StringToRecord);
        fwrite($myfile, PHP_EOL);
      }

      $Posted = "";
      foreach ($_POST as $key => $value) {
      $Posted = $Posted . "\t". htmlspecialchars($key)." ->".htmlspecialchars($value).PHP_EOL;
    }

      fclose($myfile);

      // Obtenemos las variable Posteadas.
        //Terminal.
        $terminal="";
        if (isset($_POST['Terminal']))
        {
          $terminal = $_POST['Terminal'];
        }

        // Mac Address.
        $mac="";
        if (isset($_POST['Mac']))
        {
          $mac = $_POST['Mac'];
        }

        // Creditos.
        $Creditos="";
        if (isset($_POST['Creditos']))
        {
          $Creditos = $_POST['Creditos'];
        }

        // TotalDosis A
        $TotalDosisA="";
        if (isset($_POST['TotalDosisA']))
        {
          $TotalDosisA = $_POST['TotalDosisA'];
        }

        // TotalDosis B
        $TotalDosisB="";
        if (isset($_POST['TotalDosisB']))
        {
          $TotalDosisB = $_POST['TotalDosisB'];
        }

        // ParcialDosis A
        $ParcialDosisA="";
        if (isset($_POST['ParcialDosisA']))
        {
          $ParcialDosisA = $_POST['ParcialDosisA'];
        }

        // ParcialDosis B
        $ParcialDosisB="";
        if (isset($_POST['ParcialDosisB']))
        {
          $ParcialDosisB = $_POST['ParcialDosisB'];
        }

        // Caja
        $Caja="";
        if (isset($_POST['Caja']))
        {
          $Caja = $_POST['Caja'];
        }

      $Sql = "Select * from datos where Terminal = '" . $terminal . "'";

      $result = mysqli_query ($conexion,$Sql);

      if (mysqli_affected_rows($conexion)==0) {
        // Hay alguien intentando acceder al sistema?
        // Por que llega una peticion de un terminal que no existe?

          echo "KO, No existe el terminal ".$terminal;
          die();
      }

        $row = mysqli_fetch_array($result );

        $establecimiento = $row['Establecimiento'];
        $email = $row['Email'];
        $preciopordosis = $row['PrecioPorDosis'];
        $status = $row ['Estado'];
        $bonos = $row ['Bonos'];
        $command = $row['Command'];
        $cmdvalue = $row['CmdValue'];
        $RegTotalDosisA = $row['TotalDosisA'];
        $RegTotalDosisB = $row['TotalDosisB'];


          date_default_timezone_set('Europe/Madrid');
          echo "{";
          echo '"fecha" : "'.date('d/m/Y H:i:s').'",';
          echo '"establecimiento" : "'.$establecimiento.'",';
          echo '"email" : "'.$email.'",';
          echo '"preciopordosis" : "'.$preciopordosis.'",';
          echo '"status" : "'.$status.'",';
          echo '"bonos" : "'.$bonos.'",';
          echo '"command" : "'.$command.'",';
          echo '"cmdvalue" : "'.$cmdvalue.'"';
          echo "}";




        /* Guardamos el lastAlive*/
        
        $Sql = "UPDATE datos SET  Alive = '".date('Y-m-d H:i:s')."', Command = '', CmdValue = 0, Creditos = '$Creditos', Saldo = '$Caja', TotalDosisA = '$TotalDosisA', TotalDosisB = '$TotalDosisB', ParcialDosisA = '$ParcialDosisA', ParcialDosisB = '$ParcialDosisB'  where terminal = '$terminal'";
        
        $Result = mysqli_query($conexion, $Sql);
        


        /*Anotamos los Bonos como Recibidos. */
        if ($bonos <>0){
            $ActualCredits = intval($Creditos) + intval($bonos);
            $Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja)
            values
            ('".date('Y-m-d H:i:s')."','$terminal','$establecimiento','Bonos Consumidos','$bonos Bonos','0.00 €','$ActualCredits','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja-$cmdvalue')";
            $Result = mysqli_query($conexion, $Sql);
            $Sql = "Update datos set Bonos = 0 where terminal = '$terminal'";
            $Result = mysqli_query($conexion, $Sql);
          }

         if ($TotalDosisA<$RegTotalDosisA or $TotalDosisB<$RegTotalDosisB) {
            $Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja)
            values
            ('".date('Y-m-d H:i:s')."','$terminal','$establecimiento','Descuadre de Contadores','A: $RegTotalDosisA ->$TotalDosisA B: $RegTotalDosisB->$TotalDosisB ','0.00 €','$Creditos','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja')";
            $Result = mysqli_query($conexion, $Sql);
         }
      // Funcion anulada, la envia el procesador mediante peticion.php, sino hay duplicidades

        //Anotamos el cierre de caja
      /*  if ($command =="Cierre"){
          $ActualCredits = intval($Creditos) + intval($bonos);
          $Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja)
          values
          ('".date('Y-m-d H:i:s')."','$terminal','$establecimiento','Cierre realizado','$bonos Bonos','0.00 €','$ActualCredits','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja-')";
          $Result = mysqli_query($conexion, $Sql);
      
          

          $Sql = "Update datos set Bonos = 0 where terminal = '$terminal'";
          $Result = mysqli_query($conexion, $Sql);

        }*/


        mysqli_free_result($result);
          /* cerrar la conexión */
        mysqli_close($conexion);

 ?>
