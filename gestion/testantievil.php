<?php

$Version = "3.04 22/05/2023";

date_default_timezone_set('Europe/Madrid');

include ('conexion.inc');

$Sql = "Select * from journal where operacion = 'Descuadre de Contadores' order by Id desc limit 1 ";
$result = mysqli_query ($conexion,$Sql);

if (!$result) {
    die('Invalid query: ' . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($result);

$IdDescuadre = $row['Id'];
$Terminal = $row['Terminal'];
echo ('Encontrado Descuadre de Contadores Id: '.$IdDescuadre.' -> Terminal : '.$Terminal);


$Sql = "Select * from journal where Terminal = '" . $Terminal . "' and Operacion = 'Venta' order by Id desc limit 1";
$result = mysqli_query ($conexion,$Sql);  

if (!$result) {
    die('Invalid query: ' . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($result);
$IdVenta = $row['Id'];
$Establecimiento = $row['Establecimiento'];
$Operacion = $row['Operacion'];
$TotalDosisA = $row['TotalDosisA'];
$TotalDosisB = $row['TotalDosisB'];
$Creditos = $row['Creditos'];

echo('<br>');
echo ('Id :'. $IdVenta);
echo('<br>');
echo('Establecimiento '.$Establecimiento);
echo('<br>');
echo('Operacion ').$Operacion;
echo('<br>');
echo ('Total Dosis A '.$TotalDosisA);
echo('<br>');
echo ('Total Dosis B '.$TotalDosisB);

$Sql = "insert into journal (Fecha, Terminal, Establecimiento, Operacion, Descripcion, Importe , Creditos, TotalDosisA, TotalDosisB, ParcialDosisA, ParcialDosisB, Caja)
values
('".date('Y-m-d H:i:s')."','$Terminal','9999','Desc. Corregido Automatic.','A: 9999','0.00 €','9999','9999','9999','9999','9999','9999')";
$Result = mysqli_query($conexion, $Sql);
$LastId = mysqli_insert_id($conexion);

$Sql = "Select * from journal where Terminal = '" . $Terminal . "' and Operacion = 'Bonos Añadidos'  and Id >". $IdVenta ." and Id <".$IdDescuadre. " order by Id desc" ;
echo ("<br>".$Sql);

$result = mysqli_query ($conexion,$Sql);  
if (!$result) {
    die('<br>Invalid query: ' . mysqli_error($conexion));
}

echo ('<br>Filas encontradas : '. mysqli_affected_rows($conexion));

while ($row = mysqli_fetch_assoc($result))
    {
        echo('<br>');
        $IdBonos = $row['Id'];
        echo ('Id :'. $IdBonos);
        

    }
?>