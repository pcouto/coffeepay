<?php

/* 
  Recibe las peticiones de cierre, y bonos resaliadas desde el movil

*/

date_default_timezone_set('Europe/Madrid');

include('conexion.inc');

function Reclog($StringToRecord)
{
  //exit;
  $myfile = fopen("catch.txt", "a") or die("Unable to open file!");
  //fwrite($myfile, "--------------------------------------------");
  fwrite($myfile, PHP_EOL);
  fwrite($myfile, $StringToRecord);
  fwrite($myfile, PHP_EOL);
}

foreach ($_POST as $key => $value) {
  reclog($key . '=' . $value);
}
reclog("---------------------");

$Terminal = "";
if (isset($_POST["terminal"])) {
  $Terminal = $_POST["terminal"];
}
$Importe = 0;
if (isset($_POST["importe"])) {
  $Importe = $_POST["importe"];
}
$Bonos = 0;
if (isset($_POST["bonos"])) {
  $Bonos = $_POST["bonos"];
}
$Notas = "";
if (isset($_POST["notas"])) {
  $Notas = $_POST["notas"];
}
$Cmd = "";
if (isset($_POST["cmd"])) {
  $Cmd = $_POST["cmd"];
}

$Vdata = "";
if (isset($_POST["vdata"])) {
  $Vdata = $_POST["vdata"];
}

$Usuario = "";
if (isset($_POST["usuario"])) {
  $Usuario = $_POST["usuario"];
}

reclog("Parametros Posteados");
reclog(date("Y-m-d H:m:s"));
reclog("Terminal : " . $Terminal);
reclog("Importe : " . $Importe);
reclog("Bonos : " . $Bonos);
reclog("Notas : " . $Notas);
reclog("Cmd : " . $Cmd);
reclog("vdata : " . $Vdata);
$Result = "";


$Sql = "Select * from datos where terminal = '$Terminal' limit 1";
$Result = mysqli_query($conexion, $Sql);
reclog($Sql);
if (!$Result) {
  reclog("Error al buscar el terminal");
  die("Error al buscar el numero de terminal");
}
$Row = mysqli_fetch_array($Result);
Reclog("After");
$Establecimiento = $Row["Establecimiento"];
$Creditos = $Row["Creditos"];
$TotalDosisA = $Row["TotalDosisA"];
$TotalDosisB = $Row["TotalDosisB"];
$ParcialDosisA = $Row["ParcialDosisA"];
$ParcialDosisB = $Row["ParcialDosisB"];
$Caja = $Row["Saldo"];


reclog("Comando : " . $Cmd);

if ($Cmd == "cierre") {
  $Sql = "update datos set Command = '$Cmd', CmdValue='$Importe', CmdType='1'  where Terminal = '$Terminal'";
  $Result = mysqli_query($conexion, $Sql);
  reclog("Cierre" . $Sql);
}

if ($Cmd == "cierremovil") {
  $Sql = "update datos set Command = 'Cierre', CmdValue='$Importe', CmdType='2' where Terminal = '$Terminal'";
  $Result = mysqli_query($conexion, $Sql);

  reclog("Cierre Movil " . $Sql);
  $Importe= $Importe;
  $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, OpStatus, Descripcion,Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja,Importe,Notes) 
  VALUES ('" . date('Y-m-d H:i:s') . "','$Terminal', '$Establecimiento','Solicitado Cierre Movil',2,'','$Creditos','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja',$Importe,'{Usuario : $Vdata - $Usuario}  $Notas')";
  reclog ($Sql);
  $Result = mysqli_query($conexion, $Sql);
}

if ($Cmd == "bonos") {
  $Sql = "update datos set Bonos = '$Bonos' where Terminal = '$Terminal'";
  $Result = mysqli_query($conexion, $Sql);
  reclog($Sql);
  $Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja, notes)
      values
      ('" . date('Y-m-d H:i:s') . "','$Terminal','$Establecimiento','Bonos Añadidos','$Bonos Bonos desde Disp. Móvil','0.00 €','$Creditos','$TotalDosisA','$TotalDosisB','$ParcialDosisA','$ParcialDosisB','$Caja', '$Notas')";
  reclog($Sql);
  $Result = mysqli_query($conexion, $Sql);
}
if (!$Result) {
  reclog("Error al ejecutar el mysql_query : " . $conexion->error);
} else {
  reclog("Sql ejecutado correctemante");
}
