<?php
       $servidor = 'localhost';
       $usuario = 'CoffeePay';
       $contrasena = '@CoffeePay';

       $dbname = "coffeepay";
       $conexion = new mysqli($servidor, $usuario, $contrasena, $dbname) ;

        // Si hay errores , no se continua con ellos
        /* Comprueba la conexión */
        if ($conexion->connect_errno) {
            echo("Fallo de conexion: %s\n". mysqli_connect_error());
            exit();
        }
        mysqli_set_charset($conexion, "utf8");



?>
