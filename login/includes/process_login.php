<?php

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Nuestra manera personalizada segura de iniciar sesión PHP.

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // La contraseña con hash

    if (login($email, $password, $mysqli) == true) {
        // Inicio de sesión exitosa
        header('Location: ../../gestion/datos/index.php');
    } else {
        // Inicio de sesión exitosa
        header('Location: ../index.php?error=1');
    }
} else {
    // Las variables POST correctas no se enviaron a esta página.
    echo 'Solicitud no válida';
}

 ?>
