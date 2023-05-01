<?php


include_once 'db_connect.php';
include_once 'psl-config.php';

$error_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Sanear y validar los datos provistos.
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // No es un correo electrónico válido.
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // La contraseña con hash deberá ser de 128 caracteres.
        // De lo contrario, algo muy raro habrá sucedido.
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }

    // La validez del nombre de usuario y de la contraseña ha sido verificada en el cliente.
    // Esto será suficiente, ya que nadie se beneficiará de
    // violar estas reglas.
    //




    $prep_stmt = "SELECT id, password FROM datos WHERE terminal = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);


  //  echo '<script language="javascript">alert("'.$stmt->.'");</script>';

   // Verifica que el terminal existe
    if ($stmt) {
        $stmt->bind_param('s', $codigo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Ya existe otro usuario con este correo electrónico.
            $error_msg .= '<p class="error">Código Invalido</p>';
        }
        else {
           $stmt->bind_result($rid, $rpassword);
           $stmt->fetch();
           if ($rpassword){
             header('Location: ./error.php?err=El Cliente ya esta registrado');
              die();
           }

        }

    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';

    }
  $stmt->close();

    // Verifica el nombre de usuario existente.

    /* Anulamos esta parte ya que no permite nombres de usuario iguales , y para nuestro caso nos resulta indiferente
    $prep_stmt = "SELECT id FROM datos WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

                if ($stmt->num_rows == 1) {
                        // Ya existe otro usuario con este nombre de usuario.
                        $error_msg .= '<p class="error">Ya existe un usuario con este nombre</p>';
                        $stmt->close();
                }

        } else {

                $error_msg .= '<p class="error">Database error line 55</p>';
                $stmt->close();
        }
*/
    // Pendiente:
    // También habrá que tener en cuenta la situación en la que el usuario no tenga
    // derechos para registrarse, al verificar qué tipo de usuario intenta
    // realizar la operación.

    if (empty($error_msg)) {
        // Crear una sal aleatoria.
        //$random_sign = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
        $random_sign = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

        // Crea una contraseña con sal.
        $password = hash('sha512', $password . $random_sign);

        // Inserta el nuevo usuario a la base de datos.
        //if ($insert_stmt = $mysqli->prepare("INSERT INTO datos (username, email, password, sign) VALUES (?, ?, ?, ?)")) {
          if ($insert_stmt = $mysqli->prepare("update datos set username = ?, email = ?, password = ?, sign = ? where terminal = ?")) {

            $insert_stmt->bind_param('sssss', $username, $email, $password, $random_sign, $codigo);

            // Ejecuta la consulta preparada.
            if (! $insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration failure');
            }
        }
        header('Location: ./register_success.php');
    }
}
 ?>
