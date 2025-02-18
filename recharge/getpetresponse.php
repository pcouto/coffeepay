
	<?php

	include './apiRedsys/apiRedsys.php';
	$Version = "1.02 03/04/2024";
	date_default_timezone_set('Europe/Madrid');

	// para login seguro
	include('../gestion/conexion.inc');
	$conexion->set_charset("utf8");


	if ($conexion->connect_errno) {
		reclog("Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST . "<br> User : " . USER . "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE);
		die();
	}

	header('Content-Type: text/html; charset=utf-8');


	// Se crea Objeto
	$miObj = new RedsysAPI;

	reclog(PHP_EOL . str_repeat("-", 50));
	reclog(date("d-m-Y h:i:s") . " Response Received");
	reclog(str_repeat("-", 50));
	function Reclog($StringToRecord)
	{
		/* Guardamos el XML en la carpeta correspondiente y analizamos su contenido*/
		$YearDir = date('Y');
		$MonthDir = date('m');
		$DayDir = date('d');
		if (!file_exists('./PayPet_Log' . $YearDir)) {
			mkdir('./PayPet_Log' . $YearDir, 0777, true);
		}
		if (!file_exists('./PayPet_Log' . $YearDir . '/' . $MonthDir)) {
			mkdir('./PayPet_Log' . $YearDir . '/' . $MonthDir, 0777, true);
		}

		$log_file = './PayPet_Log' . $YearDir . '/' . $MonthDir . '/' . 'PayPet_' . date('Y_m_d') . '.txt';
		$fh       = fopen($log_file, 'a') or die("error creando el fichero");
		fwrite($fh, $StringToRecord);
		fwrite($fh, PHP_EOL);
	}

	/*reclog("Datos Posteados");
	foreach ($_POST as $param_name => $param_val) {
		echo "Param: " . htmlspecialchars($param_name) . "; ";
		reclog("Param: " . htmlspecialchars($param_name) . "; ");
		echo "Value: " . htmlspecialchars($param_val) . "<br />\n";
		reclog("Value: " . htmlspecialchars($param_val) . "<br />\n");
	}

	foreach ($_GET as $param_name => $param_val) {
		echo "Param: " . htmlspecialchars($param_name) . "; ";
		reclog("Param: " . htmlspecialchars($param_name) . "; ");
		echo "Value: " . htmlspecialchars($param_val) . "<br />\n";
		reclog("Value: " . htmlspecialchars($param_val) . "<br />\n");
	}*/

	$FirmaOk = false;
	if (!empty($_POST)) { //URL DE RESP. ONLINE

		$version = $_POST["Ds_SignatureVersion"];
		$datos = $_POST["Ds_MerchantParameters"];
		$signatureRecibida = $_POST["Ds_Signature"];


		$decodec = $miObj->decodeMerchantParameters($datos);
		$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
		$firma = $miObj->createMerchantSignatureNotif($kc, $datos);

		/*echo PHP_VERSION . "<br/>";
		echo $firma . "<br/>";
		echo $signatureRecibida . "<br/>";*/
		if ($firma === $signatureRecibida) {
			$FirmaOk = true;
			//echo "FIRMA OK";
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
				$FirmaOk = true;
			}
		} else {
			reclog("No se recibió respuesta");
			die();
		}
	}

	reclog("Mensaje Recibido decodificado :" . $decodec);

	$Message = "";
	if ($firma == false) {

		$Message = "Error en la firma... ATENCION!!!";
		reclog($Message);
	} else {
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
		//$Ds_Response="202"; // OJO !!!!!!!! solo para testeo de erroress en las respuestas.
		$CodeFound = false;
		$CodeDescription = "";
		foreach ($Ds_Response_Codes as $Key) {
			if ($Key->codigo == $Ds_Response) {
				$CodeFound = true;
				$CodeDescription = $Key->descripcion;

				break;
			}
		}

		$Ds_Date = "";
		$Ds_Hour = "";
		$Ds_SecurePayment = "";
		$Ds_Amount = "";
		$Ds_Currency = "";
		$Ds_Order = "";
		$Ds_MerchantCode = "";
		$Ds_Terminal = "";
		$Ds_TransactionType = "";
		$Ds_MerchantData = "";
		$Ds_AuthorisationCode = "";
		$Ds_ConsumerLanguage = "";
		$Ds_Card_Country = "";
		$Ds_Card_Brand = "";
		$Ds_ProcessedPayMethod = "";


		$Ds_Date = str_replace("%2F", "/", $Json_Ds_Response->{'Ds_Date'});
		$Ds_Hour = str_replace("%3A", ":", $Json_Ds_Response->{'Ds_Hour'});
		$Ds_SecurePayment = $Json_Ds_Response->{'Ds_SecurePayment'};
		$Ds_Amount = $Json_Ds_Response->{'Ds_Amount'};
		$Ds_Currency = $Json_Ds_Response->{'Ds_Currency'};
		$Ds_Order = $Json_Ds_Response->{'Ds_Order'};
		$Ds_MerchantCode = $Json_Ds_Response->{'Ds_MerchantCode'};
		$Ds_Terminal = $Json_Ds_Response->{'Ds_Terminal'};
		$Ds_TransactionType = $Json_Ds_Response->{'Ds_TransactionType'};
		$Ds_MerchantData = $Json_Ds_Response->{'Ds_MerchantData'};
		$Ds_AuthorisationCode = $Json_Ds_Response->{'Ds_AuthorisationCode'};
		$Ds_ConsumerLanguage = $Json_Ds_Response->{'Ds_ConsumerLanguage'};
		$Ds_Card_Country = $Json_Ds_Response->{'Ds_Card_Country'};
		$Ds_Card_Brand = $Json_Ds_Response->{'Ds_Card_Brand'};
		$Ds_ProcessedPayMethod =  $Json_Ds_Response->{'Ds_ProcessedPayMethod'};
		// Si la operacion se realiza con Bizum.
		$Ds_Bizum_IdOper=""; 
		$Ds_Bizum_MobileNumber="";

		if (isset($Json_Ds_Response->{'Ds_Bizum_IdOper'})) {
			$Ds_Bizum_IdOper=$Json_Ds_Response->{'Ds_Bizum_IdOper'};
		}
		if (isset($Json_Ds_Response->{'$Ds_Bizum_MobileNumber'})) {
			$$Ds_Bizum_MobileNumber=$Json_Ds_Response->{'$Ds_Bizum_MobileNumber'};
		}

		/*echo ($Ds_Date . "<br>");
		echo ($Ds_Hour . "<br>");
		echo ($Ds_SecurePayment . "<br>");
		echo ($Ds_Amount . "<br>");
		echo ($Ds_Currency . "<br>");
		echo ($Ds_Order . "<br>");
		echo ($Ds_MerchantCode . "<br>");
		echo ($Ds_Terminal . "<br>");
		echo ($Ds_TransactionType . "<br>");
		echo ($Ds_MerchantData . "<br>");
		echo ($Ds_AuthorisationCode . "<br>");
		echo ($Ds_ConsumerLanguage . "<br>");
		echo ($Ds_Card_Country . "<br>");
		echo ($Ds_Card_Brand . "<br>");
		echo ($Ds_ProcessedPayMethod . "<br>");*/

		switch ($Ds_Card_Brand) {
			case 1:
				$Ds_Card_Brand = "Visa";
				break;
			case 2:
				$Ds_Card_Brand = "Mastercard";
				break;
			case 6:
				$Ds_Card_Brand = "Dinners";
				break;
			case 7:
				$Ds_Card_Brand = "Privada";
				break;
			case 8:
				$Ds_Card_Brand = "Amex";
				break;
			case 9:
				$Ds_Card_Brand = "JCB";
				break;
			case 22:
				$Ds_Card_Brand = "UPI/CUP";
				break;
			default:
				$Ds_Card_Brand = "Unkown Card";

		}

		$notes="Auth Num. : $Ds_AuthorisationCode";

		if ($Ds_ProcessedPayMethod=="89" or $Ds_Bizum_IdOper <>"")
		{
			$Ds_Card_Brand="Bizum";
			$notes = "Bizum Id Oper. (".$Ds_Bizum_MobileNumber.")". $Ds_Bizum_IdOper;
		}


		$sql = "SELECT * FROM journal WHERE Id=?"; // SQL with parameters
		$stmt = $conexion->prepare($sql);
		$stmt->bind_param("s", $Ds_Order);
		$stmt->execute();
		$result = $stmt->get_result(); // get the mysqli result
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$affectedrows = $conexion->affected_rows;

		reclog ("<br>Afected Rows on select = $affectedrows");

		reclog ("<br>Establecimiento :" . $row['Establecimiento']);
		$Id = $row['Id'];
		
		if ($Ds_Response=="0000"){
		    $Sql = "Update journal set terminal = (SELECT @terminal:= Terminal), Operacion = 'Venta  $Ds_Card_Brand', Descripcion = '($Ds_Response) $CodeDescription', Importe = " . $Ds_Amount / 100 . ",notes = '$notes' where Id = $Id";
			reclog ("<br>".$Sql);
			$Result = mysqli_query($conexion, $Sql);
			$Sql = "Select * from datos where Terminal = @terminal";
			$result = mysqli_query($conexion, $Sql);
			$row = $result -> fetch_array(MYSQLI_ASSOC);
			$PPD = $row["PrecioPorDosis"];
			$Terminal = $row["Terminal"];
			$Dosis = intval($Ds_Amount/100/$PPD);
			$Remain = $Ds_Amount/100-$PPD*$Dosis;
			$Sql = "Update datos set Bonos = $Dosis where terminal = $Terminal";
			reclog ("<br>".$Sql);
			$result = mysqli_query($conexion, $Sql);
			//reclog "<br>Terminal : ". $Terminal. " Importe : ".$Ds_Amount/100 . " PPD : ".$PPD. " Dosis : ".$Dosis. " Remain : ". $Remain;
			$Sql = "Update journal set descripcion = 'Añadidas $Dosis Dosis (Ppd = $PPD €)' where Id = $Id";
			$result = mysqli_query($conexion, $Sql);
			reclog ("<br>".$Sql);
		
		}
		else
		{
			$Sql = "Update journal set Operacion = 'Operación Denegada', Descripcion = '($Ds_Response) $CodeDescription' where Id = $Id";
			//reclog $Sql;
			$Result = mysqli_query($conexion, $Sql);
		
		}
		

		if (!$Result){
			reclog ("<br>No se ejecuto correctamente la petición Update");
		}
		else
		{
			reclog ("<br>Update realizado");
		}

	}


	?>