<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Secure Login: Log In</title>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
    </head>
    <body>
      <div class="MainLog" align = "center">

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
        ?>
        <form action="includes/process_login.php" method="post" name="login_form">
            Correo electrónico: <input type="text" name="email" />
            Contraseña: <input type="password"
                             name="password"
                             id="password"/>
            <input type="button"
                   value="Login"
                   onclick="formhash(this.form, this.form.password);" />
        </form>
        <p> Si no tiene una cuenta, por favor <a href="register.php">regístrese.</a></p>
        <p> Si ha terminado, por favor <a href="logout.php">cierre la sesión.</a></p>
        <p>Está conectado. <?php echo $logged ?>.</p>
        </div>
    </body>
</html>
