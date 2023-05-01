
<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Formulario de registro</title>
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="styles/main.css" />
        <!--===============================================================================================-->
        	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
        <!--===============================================================================================-->
        	<link rel="stylesheet" type="text/css" href="css/util.css">
        	<link rel="stylesheet" type="text/css" href="css/main.css">
        <!--===============================================================================================-->
    </head>
    <body>
        <!-- Formulario de registro que se emitirá si las variables POST no se
          establecen o si la secuencia de comandos de registro ha provocado un error. -->

        <div class="limiter">


      		<div class="container-login100">

      			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">


                <form class="login100-form validate-form flex-sb flex-w"
                        action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>"
                        method="post"
                        name="registration_form">
                        <span class="login100-form-title p-b-32">
              						Proceso de Registro
                            <?php
                              if (!empty($error_msg)) {
                                  echo "<font style = 'text-decoration: underline'; color = 'orange';>$error_msg</font>";
                              }
                            ?>
              					</span>

                        <span class="txt2 p-b-3">Código: </span>
                        <div class="wrap-input100 validate-input m-b-12" data-validate = "Código Requerido">
              						<input class="input100" type="text" name="codigo" id="codigo">
              						<span class="focus-input100"></span>
              					</div><br>

                      <span class="txt2 p-b-3">Nombre de Usuario </span>
                      <div class="wrap-input100 validate-input m-b-12" data-validate = "Usuario Requerido">
            						<input class="input100" type="text" name="username" id='username'>
            						<span class="focus-input100"></span>
            					</div><br>

                      <span class="txt2 p-b-3">Correo Electrónico </span>
                      <div class="wrap-input100 validate-input m-b-12" data-validate = "Usuario Requerido">
            						<input class="input100" type="text" name="email" id="email">
            						<span class="focus-input100"></span>
            					</div>

                      <span class="txt2 p-b-3">Contraseña</span>
                      <div class="wrap-input100 validate-input m-b-12" data-validate = "Password Requerido">
                        <input class="input100" type="password" name="password" id="password">
                        <span class="focus-input100"></span>
                      </div>

                      <span class="txt2 p-b-3">Confirmar Contraseña</span>
                      <div class="wrap-input100 validate-input m-b-12" data-validate = "Password Requerido">
                        <input class="input100" type="password" name="confirmpwd" id="confirmpwd">
                        <span class="focus-input100"></span>
                      </div>




                      <div class="flex-sb-m w-full p-b-48">
                        <input class="login100-form-btn" type="button"
                               value="Registrarse"
                               onclick="return regformhash(this.form,
                                               this.form.codigo,
                                               this.form.username,
                                               this.form.email,
                                               this.form.password,
                                               this.form.confirmpwd);" />
                              <div class="txt3">

                                          <a href="index.php" >
                                             Identificarse
                                          </a>
                              </div>
                    </div>
                      <p class = "txt2">Pulsando en "<b>Registrarse</b>", Vd., accepta las condiciones de uso.
                        Puede leer las condiciones de uso <a href="Terminos y condiciones de uso.pdf" >aquí</a>.
                      </p>

                </form>


                </div>
              </div>
            </div>


    </body>
</html>
