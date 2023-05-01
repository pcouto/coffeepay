<?php

    include_once 'psl-config.php';   // Ya que functions.php no está incluido.
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br> Host : " . HOST. "<br> User : ". USER. "<br> Passwd" . PASSWORD . "<br> Database : " . DATABASE;
		
    die();
    }


 ?>
