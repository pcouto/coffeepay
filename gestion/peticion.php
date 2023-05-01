<?php
/*******************************************************************************
      Peticion
      Descripcion         : Servicio que recibe la peticion de los billeteros
      Fecha Inicio        : 15/02/2020
      Fecha modificacion  : 15/02/2020
      Version             : 1.1
      Author              : Pedro Couto
********************************************************************************/

date_default_timezone_set('Europe/Madrid');

      //Importamos los ficheros de geolocalizacion
      require('getip.php');
      // Captura la ip de la peticion
      require_once('./lib/geoplugin.class.php');    // Geolocaliza la ip
      // y los de la base de datos

      include ('conexion.inc');

      // y las funciones
      include ('functions.inc');
      // Encriptacion
      include ('encdec.inc');

      /**********************************************************************************************************************
                  Recogemos el xml procedente del terminal  analizamos su contenido.
      ***********************************************************************************************************************/
      $Xml_Post = file_get_contents('php://input');

      //Si no ha entrado un xml cerramos sin devolver nada para no dar pistas.
      if(!$Xml_Post) {die("error de post");}


      /* Guardamos el XML en la carpeta correspondiente y analizamos su contenido*/
      $YearDir = date('Y');
      $MonthDir = date ('m');
      $DayDir = date ('d');
      if (!file_exists('./received_'.$YearDir)) { mkdir('./received_'.$YearDir, 0777, true); }
      if (!file_exists('./received_'.$YearDir.'/'.$MonthDir)) { mkdir('./received_'.$YearDir.'/'.$MonthDir, 0777, true); }
      //if (!file_exists('./received_'.$YearDir.'/'.$MonthDir.'/'.$DayDir)) { mkdir('./received_'.$YearDir.'/'.$MonthDir.'/'.$DayDir, 0777, true); }

      $xml_file = './received_'.$YearDir.'/'. $MonthDir .'/'. 'received_xml_' . date('Y_m_d') . '.txt';
      $fh       = fopen( $xml_file, 'a') or die("error creando el fichero");
      fwrite($fh, str_repeat ("-" ,30).'\r\n');
      fwrite($fh, $Xml_Post);

      // Leemos los datos del POST


      $Terminal ="--";
      if (isset ($_POST["Terminal"]))
          {
            $Terminal = $_POST["Terminal"];
          }

      $Establecimiento ="";
      if (isset ($_POST["Establecimiento"]))
          {
            $Establecimiento = $_POST["Establecimiento"];
          }

      $Operacion ="";
      if (isset ($_POST["Operacion"]))
          {
            $Operacion = $_POST["Operacion"];
          }


      $Descripcion ="";
      if (isset ($_POST["Descripcion"]))
          {
            $Descripcion = $_POST["Descripcion"];
          }

          $TotalDosisA =0;
          if (isset ($_POST["TotalDosisA"]))
              {
                $TotalDosisA = $_POST["TotalDosisA"];
              }

          $TotalDosisB =0;
          if (isset ($_POST["TotalDosisB"]))
              {
                $TotalDosisB = $_POST["TotalDosisB"];
              }

          $ParcialDosisA =0;
          if (isset ($_POST["ParcialDosisA"]))
              {
                $ParcialDosisA = $_POST["ParcialDosisA"];
              }

          $ParcialDosisB =0;
          if (isset ($_POST["ParcialDosisB"]))
              {
                $ParcialDosisB = $_POST["ParcialDosisB"];
              }

          $Importe =0;
          if (isset ($_POST["Importe"]))
              {
                $Importe = $_POST["Importe"];
              }

          $Creditos =0;
          if (isset ($_POST["Creditos"]))
              {
                $Creditos = $_POST["Creditos"];
              }


          $Caja =0;
          if (isset ($_POST["Caja"]))
              {
                 $Caja = $_POST["Caja"];
              }
          $Notes = "";
          if (isset ($_POST["Notes"]))
              {
                 $Caja = $_POST["Notes"];
              }

      // Geolocalizamos la peticion
      $GeoPlugin = new geoPlugin();
      $GeoPlugin->locate($IP);
      $City = $GeoPlugin->city;
      $Country = $GeoPlugin->countryName;

      // Intento de inyeccion sql?
      if (strlen($Terminal)>8)
            {
              recalarm("Longitud de terminal erronea : $Terminal" , 9);
              die();
            }



      $Sql = "Select * from datos where terminal  = '$Terminal'";

      $Result = mysqli_query($conexion, $Sql);
      if (!$Result)
        {
          die("error");
        }

      if (mysqli_affected_rows($conexion)==0)
        {
          //Temporal para las altas automaticas
          $Sql = "insert into datos (terminal) values ('$Terminal')";
          $Result = mysqli_query($conexion, $Sql);
          //die("No existe el terminal $Terminal");
        }
      $Row = mysqli_fetch_array( $Result);

      $Estado = $Row["Estado"];
      $RecordedCity =  $Row["City"];
      $RecordedCountry =  $Row["Country"];
      $NumOperations = $Row["Numoperations"];
      $PrecioPorDosis = $Row["PrecioPorDosis"];
      $Bonos= $Row["Bonos"];

      // Si es la primera operacion, y no tiene grabada la identificacion IIP de ciudad y numoperations
      // permitimos que se guarde por primera vez
      if(empty($RecordedCountry) and empty($num_operations ))
      {
        $Sql = "update datos set country = '$Country', city='$City' where terminal = '$Terminal'";
        $Result = mysqli_query($conexion, $Sql);
        $RecordedCity =$City;
        $RecordedCountry=$Country;
      }

      // Verificamos que ciudad y pais son los esperasdos, sino generamos una alarma
      if ($RecordedCity !=$City Or  $RecordedCountry!=$Country)
      {
        recalarm("La ip no coincide en localizacion de ciudad", 7);
        //die();
      }


      // Componemos la respuesta

      $StrXML =  "<?xml version='1.0' encoding='UTF-8'?>
        <REQUEST type = 'RESP' mode='' >
          <TERMINAL>$Terminal</TERMINAL>
          <FECHA>".date('Y-m-d H:i:s') ."</FECHA>
          <ESTABLECIMIENTO>$Establecimiento</ESTABLECIMIENTO>
          <PRECIOPORDOSIS>$PrecioPorDosis</PRECIOPORDOSIS>
          <OPERACION>$Operacion</OPERACION>
          <ESTADO>$Estado</ESTADO>
          <BONOS>$Bonos</BONOS>
        </REQUEST>";


        fwrite($fh, $StrXML);

      if ($Operacion=="Venta"){
        $Descripcion =    "Precio : " .$PrecioPorDosis . " € por dosis";
      }

      
      if ($Operacion=="Cierre diferido"){
        $Descripcion =    "Cierre en Oficina";
      }

      $Sql = "Insert into journal (Fecha,Terminal,Establecimiento,Operacion,Descripcion,Importe,Creditos,TotalDosisA,TotalDosisB,ParcialDosisA,ParcialDosisB,Caja,Notes)
              values
              ('".date('Y-m-d H:i:s')."', '$Terminal','$Establecimiento','$Operacion','$Descripcion','$Importe','$Creditos',$TotalDosisA,$TotalDosisB,$ParcialDosisA,$ParcialDosisB,'$Caja','$Notes' )";


      $Result = mysqli_query($conexion, $Sql);

      $Sql = "update datos set numoperations = numoperations + 1, saldo = '$Caja', lastopdate = '".date('Y-m-d H:i:s')."' where terminal = '$Terminal'";

      $Result = mysqli_query($conexion, $Sql);

      echo ($StrXML);

 ?>