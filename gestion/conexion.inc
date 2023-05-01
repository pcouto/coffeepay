<?php
       $servidor = 'localhost';
       $usuario = 'CoffeePay';
       $contrasena = '@CoffeePay';

       $dbname = "coffeepay";
       $conexion = mysqli_connect($servidor, $usuario, $contrasena, $dbname) ;

        // Si hay errores , no se continua con ellos
        /* Comprueba la conexión */
        if (mysqli_connect_errno()) {
            echo("Fallo de conexion: %s\n". mysqli_connect_error());
            exit();
        }
        mysqli_set_charset($conexion, "utf8");



?>
