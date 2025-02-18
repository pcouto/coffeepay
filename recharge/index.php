<?php
$Version = "1.02 03/04/2024";
date_default_timezone_set('Europe/Madrid');

// para login seguro
include ('../gestion/conexion.inc');
$conexion->set_charset("utf8");

if ($conexion->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST . "<br> User : " . USER . "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
    die();
}

header('Content-Type: text/html; charset=utf-8');

if (isset($_GET["terminal"])) {
    $Terminal =  $_GET["terminal"];
} else {
    //Matamos el proceso sin mas explicaciones
    die();
}
//echo "T : $Terminal <br>";
$Terminal = (string) $Terminal;
$sql = "SELECT * FROM datos WHERE Terminal=?"; // SQL with parameters
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $Terminal);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result


$row = $result->fetch_array(MYSQLI_ASSOC);
$affectedrows = $conexion->affected_rows;
if ($affectedrows == 0){
    die("No data available. Try Later!..");
}
$Id =  $row["Id"];
$Terminal = $row["Terminal"];
$Establecimiento = $row["Establecimiento"];
$Saldo = $row["Saldo"];
$PrecioPorDosis =  $row["PrecioPorDosis"];
$Alive =  $row["Alive"];
$Creditos = $row["Creditos"];
$TotalDosisA = $row["TotalDosisA"];
$TotalDosisB = $row["TotalDosisB"];
$ParcialDosisA = $row["ParcialDosisA"];
$ParcialDosisB = $row["ParcialDosisB"];
/*echo "Id : $Id <br>";
echo "Terminal : $Terminal <br>";
echo "Establecimiento : $Establecimiento <br>";
echo "Saldo : $Saldo <br>";
echo "Precio Por Dosis : $PrecioPorDosis <br>";
echo "Alive : $Alive <br>";*/

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>recargas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>

<body>
    
    <div class="text-center p-5 mb-4 bg-light round-3">
        <div class="container-fluid text-center py-5">
            <h1 class="display-5 fw-bold">COFFEEPAY</h1>
            <form action="payment.php" method="post">
                <div class="text-center">
                    <input type="hidden" name="Id" value="<?php echo $Id; ?>">
                    <input type="hidden" name="Terminal" value="<?php echo $Terminal; ?>">
                    <input type="hidden" name="Establecimiento" value="<?php echo $Establecimiento; ?>">
                    <input type="hidden" name="Saldo" value="<?php echo $Saldo; ?>">
                    <input type="hidden" name="Creditos" value="<?php echo $Creditos; ?>">
                    <input type="hidden" name="PrecioPorDosis" value="<?php echo $PrecioPorDosis; ?>">
                    <input type="hidden" name="Alive" value="<?php echo $Alive; ?>">
                    <input type="hidden" name="TotalDosisA" value="<?php echo $TotalDosisA; ?>">
                    <input type="hidden" name="TotalDosisB" value="<?php echo $TotalDosisB; ?>">
                    <input type="hidden" name="ParcialDosisA" value="<?php echo $ParcialDosisA; ?>">
                    <input type="hidden" name="ParcialDosisB" value="<?php echo $ParcialDosisB; ?>">
                    
                    <p class="border-1 shadow"><br>Mediante esta aplicación Vd. puede recargar su terminal con tarjeta de crédito, Bizum o Paypal.&nbsp;<br><br>El uso de esta aplicación implica la aceptación de los términos de uso.<br><br>Puede obtener una copia de dichos términos pulsando sobre este&nbsp;<a href="#"><span style="color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1));">enlace</span></a>.<br><br></p>
                </div>
                <button type="submit" class="btn btn-primary btn-lg text-center" style="box-shadow: 3px 3px 20px 0px var(--bs-btn-bg);margin: 18px;">Aceptar las Condiciones y Continuar</button>
            </form>
        </div>
    </div>
    <h1 class="text-center"></h1>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>