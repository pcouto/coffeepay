<?php
$Version = "1.02 03/04/2024";
date_default_timezone_set('Europe/Madrid');

// para login seguro
define("HOST", "localhost");     // El alojamiento al que deseas conectarte
define("USER", "CafeDueTazze");    // El nombre de usuario de la base de datos
define("PASSWORD", "@CafeDueTazze");    // La contraseña de la base de datos
define("DATABASE", "CafeDueTazze");    // El nombre de la base de datos

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

define("SECURE", FALSE);    // ¡¡¡SOLO PARA DESARROLLAR!!!!

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
$mysqli -> set_charset("utf8");


if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST . "<br> User : " . USER . "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
    die();
}

header('Content-Type: text/html; charset=utf-8');

if (isset($_GET["terminal"])){
    $Terminal =  $_GET["terminal"];
}
else
{
    //Matamos el proceso sin mas explicaciones
    die ();
}
echo "T : $Terminal <br>";
$Terminal = (String) $Terminal;
$sql = "SELECT * FROM datos WHERE Terminal=?"; // SQL with parameters
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $Terminal);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result

$row = $result->fetch_array(MYSQLI_ASSOC);
$Id =  $row["Id"];
$Terminal = $row["Terminal"];
$Establecimiento = $row["Establecimiento"];
$Saldo = $row["Saldo"];
$PrecioPorDosis =  $row["PrecioPorDosis"];
$Alive =  $row["Alive"];

echo "Id : $Id <br>";
echo "Terminal : $Terminal <br>";
echo "Establecimiento : $Establecimiento <br>";
echo "Saldo : $Saldo <br>";
echo "Precio Por Dosis : $PrecioPorDosis <br>";
echo "Alive : $Alive <br>";




?>

<html>

<head>

</head>

<body>

</body>

</html>