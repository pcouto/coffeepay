<?php

/*******************************************************************************
      Finduser
      Descripcion         : Busca un usuario y mira si esta autorizado
      Fecha Inicio        : 22/12/2022
      Fecha modificacion  : 22/12/2022
      Version             : 1.1
      Author              : Pedro Couto
********************************************************************************/



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
      date_default_timezone_set('Europe/Madrid');

      $Xml_Post = file_get_contents('php://input');

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

      //Si no ha entrado un xml cerramos sin devolver nada para no dar pistas.
      if(!$Xml_Post) {die("error de post");}


      $Terminal ="--";
      if (isset ($_POST["Terminal"]))
          {
            $Terminal = $_POST["Terminal"];
          }

      $Usuario ="";
      if (isset ($_POST["Usuario"]))
          {
            $Usuario = $_POST["Usuario"];
          }

      $Mac ="";
      if (isset ($_POST["Mac"]))
          {
            $Mac = $_POST["Mac"];
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



          $Sql = "Select * from usuarios where Usuario  = '$Usuario'";

          $Result = mysqli_query($conexion, $Sql);
          if (!$Result)
            {
              die("error");
            }

          if (mysqli_affected_rows($conexion)==0)
            {
              $Row = mysqli_fetch_array( $Result);
              $nombre = $Row["Nombre"];
              $passwd = $Row["Passwd"];
              $estado = $Row["Estado"];
              echo "{";
              echo '"fecha" : "'.date('d/m/Y H:i:s').'",';
              echo '"nombre" : "'.$nombre.'",';
              echo '"passwd" : "'.$passwd.'",';
              echo '"status" : "'.$status.'",';
              echo "}";

            }
          else
            {
              echo "{";
              echo '"fecha" : "'.date('d/m/Y H:i:s').'",';
              echo '"nombre" : "-",';
              echo '"passwd" : "-",';
              echo '"status" : "0",';
              echo "}";

            }

            mysqli_free_result($result);
              /* cerrar la conexión */
              mysqli_close($conexion);
 ?>
