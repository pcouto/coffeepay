<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'Conectado';
} else {
    $logged = 'Desconectado';
}

	/*
	echo ("Host : ". HOST)."<br>";
	echo ("User : ". USER)."<br>";
	echo ("Passwd : ". PASSWORD)."<br>";
	echo ("Database : ". DATABASE)."<br>";
	*/

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Datos de recarga</title>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
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

    </head>
    <body>



        <div class="limiter">
      		<div class="container-login100">

      			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
              <?php
              if (isset($_GET['error'])) {
                  echo  '<b><font color="red" >Usuario o contraseña incorrectos!</font></b>';
              }
              ?>
      				<form action="includes/process_login.php" method="post">
      					<span class="login100-form-title p-b-32">
      						Acceso de Usuarios
      					</span>

      					<span class="txt1 p-b-11">
      						Email
      					</span>
      					<div class="wrap-input100 validate-input m-b-36" data-validate = "Email obligatorio">
                  <input class="input100" type="text" name="email" />
      						<span class="focus-input100"></span>
      					</div>

      					<span class="txt1 p-b-11">
      						Contraseña
      					</span>
      					<div class="wrap-input100 validate-input m-b-12" data-validate = "Contraseña obligatoria">
      						<span class="btn-show-pass">
      							<i class="fa fa-eye"></i>
      						</span>
      						<input class="input100" type="password" name="password" >
      						<span class="focus-input100"></span>
      					</div>

      					<div class="flex-sb-m w-full p-b-48">
      						<div class="contact100-form-checkbox">
      							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
      							<label class="label-checkbox100" for="ckb1">
      								Recuerdame
      							</label>
      						</div>

      						<div >
      							<a href="#" class="txt3">
      								Olvide la contraseña
      							</a>
      						</div>
      					</div>

                <div class="flex-sb-m w-full p-b-30">
      					     <div class="container-login100-form-btn">
      						           <button class="login100-form-btn"value="Login"
                             onclick="formhash(this.form, this.form.password);">
      							                Entrar
                                  </button>
                      </div>
                      <div  class ="txt3" >
                        <a href="./register.php">Registrarse.</a>
                      </div>

      					  </div>
                  <div class="flex-sb-m w-full p-b-10">
                    <div  class ="txt3" >
                      <a href = "mailto: info@knessen.com">info@knessen.com</a>
                    </div>
                    <div  class ="txt3" >
                      <a>Tel: 902 360 050</a>
                    </div>
                  </div>
      				</form>

      			</div>

      		</div>

      	</div>


      	<div id="dropDownSelect1"></div>

      <!--===============================================================================================-->
      	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
      <!--===============================================================================================-->
      	<script src="vendor/animsition/js/animsition.min.js"></script>
      <!--===============================================================================================-->
      	<script src="vendor/bootstrap/js/popper.js"></script>
      	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <!--===============================================================================================-->
      	<script src="vendor/select2/select2.min.js"></script>
      <!--===============================================================================================-->
      	<script src="vendor/daterangepicker/moment.min.js"></script>
      	<script src="vendor/daterangepicker/daterangepicker.js"></script>
      <!--===============================================================================================-->
      	<script src="vendor/countdowntime/countdowntime.js"></script>
      <!--===============================================================================================-->
      	<script src="js/main.js"></script>








    </body>
</html>
