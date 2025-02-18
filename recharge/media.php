<?php

$Version = "1.02 03/04/2024";
date_default_timezone_set('Europe/Madrid');

// para login seguro
include ('../gestion/conexion.inc');
include './apiRedsys/apiRedsys.php'; // incluimos el api de redsys

$conexion->set_charset("utf8");

if ($conexion->connect_errno) {
    echo "Falló la conexión a MySQL: (";// . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST . "<br> User : " . USER . "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
    die();
}

header('Content-Type: text/html; charset=utf-8');
//Generamos el numero de pedido


$Id = "";
if (isset($_POST["Id"])) {
    $Id = $_POST["Id"];
}

if ($Id = "") {
    die("No Data available for payment. Try Later.");
}

$Id = $_POST["Id"];
$Terminal = $_POST["Terminal"];
$Establecimiento = $_POST["Establecimiento"];
$Importe = $_POST["Importe"];

$Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion) values (now(),'$Terminal','$Establecimiento','Solicitud venta tarjeta','$Importe Euros')";

$Result = mysqli_query($conexion, $Sql);
if (!$Result)
  {
    die("error");
  }

$Order= mysqli_insert_id($conexion);

$Sql = "Update journal set notes = '$Order' where id = $Order";
//echo $Sql;
$Result= mysqli_query($conexion,$Sql);

//echo "<br>GenId = $Order";

$miObj = new RedsysAPI;

	// Valores de entrada que no hemos cmbiado para ningun ejemplo
	$MerchantCode="362349086";
	$terminal="001";
	$moneda="978";
	$trans="0";
	$url="";
	$urlOKKO="https://knessen.com/coffeepay/recharge/getpetresponse2.php";
	$url ="https://knessen.com/coffeepay/recharge/getpetresponse.php";
	$id=time();
	$amount="145";	
	
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$Importe*100);
	$miObj->setParameter("DS_MERCHANT_ORDER",$Order);
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$MerchantCode);
	$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
	$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal); 
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOKKO);
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlOKKO);

	//Datos de configuración
	$version="HMAC_SHA256_V1";
	$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';//Clave recuperada de CANALES
    // Se generan los parámetros de la petición
	$request = "";
	$params = $miObj->createMerchantParameters();
	$signature = $miObj->createMerchantSignature($kc);

 
	//echo "Version de PHP :".phpversion();
    
?>


<html lang="es">
<head>
</head>
<body>
    <p>Enviando Petición</p>
    <p>Por Favor Espere...</p>
<form Id = "MyForm" name="frm" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
<input type="hidden" name="Ds_SignatureVersion" value="<?php echo $version; ?>"/></br>
<input type="hidden" name="Ds_MerchantParameters" value="<?php echo $params; ?>"/></br>
<input type="hidden" name="Ds_Signature" value="<?php echo $signature; ?>"/></br>

</form>
<script type="text/javascript">
   document.getElementById('MyForm').submit();
</script>
</body>
</html>


