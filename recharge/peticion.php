<?php

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
$Saldo = $_POST["Saldo"];
$PrecioPorDosis = $_POST["PrecioPorDosis"];
$Creditos = $_POST["Creditos"];
$Alive = $_POST["Alive"];
$Importe =  $_POST["Importe"];
$TotalDosisA = $_POST["TotalDosisA"];
$TotalDosisB = $_POST["TotalDosisB"];
$ParcialDosisA = $_POST["ParcialDosisA"];
$ParcialDosisB = $_POST["ParcialDosisB"];
//echo ("Terminal $Terminal");

$Version = "1.03 22/04/2024";
date_default_timezone_set('Europe/Madrid');

include('../gestion/conexion.inc');

$conexion->set_charset("utf8");


if ($conexion->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST . "<br> User : " . USER . "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
    die();
}

header('Content-Type: text/html; charset=utf-8');



$Terminal = (string) $Terminal;
/*
//$Sql = "SELECT * FROM datos WHERE Terminal=?"; // SQL with parameters
//echo "$Importe  -> $PrecioPorDosis";
$Bonos = $Importe / $PrecioPorDosis;
//$Sql = "Update datos set bonos = $Bonos where terminal = '$Terminal'";
$Sql = "update datos set bonos = $Bonos where terminal= '$Terminal'";
//echo $Sql;
$Result = $conexion->query($Sql);
//echo "Afected:".$conexion->affected_rows."Error :".$conexion->error;

if (!$Result) {
    die('Consulta no válida: ' . $conexion->error);
}

$Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja)
values
('" . date('Y-m-d H:i:s') . "','$Terminal','$Establecimiento','Venta Visa','$Bonos Dosis','$Importe €','$Creditos','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Saldo')";
$Result = $conexion->query($Sql);
header("refresh:10; url=index.php");*/

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>recargas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <script>
        vSelected = document.getElementById("selectorPrecios");
    </script>
</head>

<body style="text-align: center;">
    <div style="text-align: center;"></div>
    <h1 style="text-align: center;">CoffeePay Medios de Pago</h1>
    <p class="border-1" style="margin-top: 16px;">Recarga de <?php echo $Importe; ?> Euros Realizada Correctamente. &nbsp;<label class="form-label" id="Dosis"></label></p>
    <p class="border-1" style="margin-top: 16px;">Terminal : <?php echo $Terminal; ?>&nbsp;<label class="form-label" id="Dosis"></label></p>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>