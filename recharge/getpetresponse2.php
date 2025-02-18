<?php

	include './apiRedsys/apiRedsys.php';

	// Se crea Objeto
	$miObj = new RedsysAPI;


	//Echo date("D-m-Y h:i:s")." Response Received<br>";
	reclog (PHP_EOL.str_repeat ("-" ,50));
	reclog(date("d-m-Y h:i:s") . " Response Received");
	reclog (str_repeat ("-" ,50));
	function Reclog($StringToRecord)
	{
      /* Guardamos el XML en la carpeta correspondiente y analizamos su contenido*/
      $YearDir = date('Y');
      $MonthDir = date ('m');
      $DayDir = date ('d');
      if (!file_exists('./PayPet_Log'.$YearDir)) { mkdir('./PayPet_Log'.$YearDir, 0777, true); }
      if (!file_exists('./PayPet_Log'.$YearDir.'/'.$MonthDir)) { mkdir('./PayPet_Log'.$YearDir.'/'.$MonthDir, 0777, true); }
      //if (!file_exists('./received_'.$YearDir.'/'.$MonthDir.'/'.$DayDir)) { mkdir('./received_'.$YearDir.'/'.$MonthDir.'/'.$DayDir, 0777, true); }

      $xml_file = './PayPet_Log'.$YearDir.'/'. $MonthDir .'/'. 'PayPet_Log' . date('Y_m_d') . '.txt';
      $fh       = fopen( $xml_file, 'a') or die("error creando el fichero");
      //fwrite($fh, str_repeat ("-" ,30).PHP_EOL);
      fwrite($fh, $StringToRecord);
	  fwrite($fh, PHP_EOL);
	}

	reclog("Datos Posteados");
	foreach ($_POST as $param_name => $param_val) {
		reclog(" Con Post<br>");
		echo "Param: " . htmlspecialchars($param_name) . "; ";
		reclog("Param: " . htmlspecialchars($param_name) . "; ");
		echo "Value: " . htmlspecialchars($param_val) . "<br />\n";
		reclog("Value: " . htmlspecialchars($param_val) . "<br />\n");
	}

	foreach ($_GET as $param_name => $param_val) {
		reclog(" Con Get<br>");
		//echo "Param: ".htmlspecialchars($param_name)."; ";
		reclog("Param: " . htmlspecialchars($param_name) . "; ");
		//echo "Value: " . htmlspecialchars($param_val) . "<br />\n";
		reclog("Value: " . htmlspecialchars($param_val) . "<br />\n");
	}

	$FirmaOk = false;
	if (!empty($_POST)) { //URL DE RESP. ONLINE

		$version = $_POST["Ds_SignatureVersion"];
		$datos = $_POST["Ds_MerchantParameters"];
		$signatureRecibida = $_POST["Ds_Signature"];


		$decodec = $miObj->decodeMerchantParameters($datos);
		$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
		$firma = $miObj->createMerchantSignatureNotif($kc, $datos);

		echo PHP_VERSION . "<br/>";
		echo $firma . "<br/>";
		echo $signatureRecibida . "<br/>";
		if ($firma === $signatureRecibida) {
			//echo "FIRMA OK";
			$Firma = true;
		} else {
			//echo "FIRMA KO";
		}
	} else {
		if (!empty($_GET)) { //URL DE RESP. ONLINE

			$version = $_GET["Ds_SignatureVersion"];
			$datos = $_GET["Ds_MerchantParameters"];
			$signatureRecibida = $_GET["Ds_Signature"];


			$decodec = $miObj->decodeMerchantParameters($datos);
			$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
			$firma = $miObj->createMerchantSignatureNotif($kc, $datos);

			if ($firma === $signatureRecibida) {
				//echo "FIRMA OK";
				$firma = true;
			} else {
				//echo "FIRMA KO";
			}
		} else {
			die("No se recibió respuesta");
		}
	}
	//echo ("Mensaje:" . $decodec);
	reclog("Mensaje:" . $decodec);

	if ($firma == false) {
		reclog("Error en la firma...... ATENCION!");
		echo ("<br>Error en la firma.<br>No se puede continuar");
		echo ("<br>Intentelo de nuevo, si el error persiste");
		echo ("<br>Pongase en contacto con su proveedor");
		die();
	}


	$Json_Ds_Response = json_decode($decodec);
	$Ds_Response = $Json_Ds_Response->{'Ds_Response'};
	//echo "Ds_Response: $Ds_Response"; // Respuesta desde Visa decodificada
	if (intval($Ds_Response) <= 99 and intval($Ds_Response >= 0)) {
		$Ds_Response = "0000";
	}

	$JsonResponseCodes = file_get_contents('ds_response_codes.json'); // CArgamos los códigos de nuestro fichero de codigos json
	$Ds_Response_Codes = json_decode($JsonResponseCodes);

	//echo "<br>Response Codes:" . $JsonResponseCodes;

	//echo "<br>Codigo : " . $Ds_Response_Codes[0]->codigo;

	// Buscamos el código de respuesta
	// $Ds_Response="909"; // OJO !!!!!!!! solo para testeo de erroress en las respuestas.
	$CodeFound = false;
	$CodeDescription = "";
	foreach ($Ds_Response_Codes as $Key) {
		if ($Key->codigo == $Ds_Response) {
			$CodeFound = true;
			$CodeDescription = $Key->descripcion;
			//echo "<br> $Key->codigo - $Key->descripcion";
			break;
		}
	}


	//echo $Ds_Response. " " . $CodeDescription;
	//echo "Código : $Ds_Response Descripción : $CodeDescription";

	$Line1 = ""; // contenido de las lineas de texto que apareceran en la pagina web
	$Line2 = "";
	$Line3 = "";
	$TextColor = "text-success";
	// Si la respuesta ha sido correcta.-
	If ($Ds_Response == "0000"){
		$Line1 = "Su Pedido se ha procesado correctamente";
		$Line2 = "Compruebe que los créditos se han agregado a su terminal. ";
		$Line3 = "El proceso puede tardar hasta 3 minutos, por favor sea paciente";
	}
	// Si no lo ha sido
	else 
	{
		$Line1 = "Opps. Ha habido algún problema.";
		$Line2 = "No se ha podido Procesar su solicitud.";
		$Line3 =  $CodeDescription;
		$TextColor = "text-Danger";
	}
	// Si no se encontro el código de respuesta.
	if (!$CodeFound){
		$Line1 = "Error procesando Respuesta";
		$Line2 = "Error Interno";
		$Line3 = "Ds_Response : $Ds_Response";
		$TextColor = "text-Danger";
	}


	?>

<html>
	<head>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	</head>

<body>	
    <div style="text-align: center;">
    	<label class="border border-success mt-4 shadow w-50"><h1>CoffeePay Medios de Pago</h1></label>
			<h2 class = <?php echo $TextColor?>><?php echo "<br>".$Line1?></h2>
			<p><?php  echo $Line2?> </p>
			<p><?php  echo $Line3?></p>
			<p>Si tiene cualquier duda, pongase en contacto con su proveedor.</p>
			<p>Gracias por usar nuestros servicios.</p>
			<button class="btn btn-primary btn-lg text-center" style="box-shadow: 3px 3px 20px 0px var(--bs-btn-bg);margin: 18px;" id="redirectButton" onclick="window.location='https://knessen.com'">Finalizar</button>
			
	</div>
</body>

</html>