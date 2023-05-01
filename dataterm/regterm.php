<?php
      //error_reporting(0);
      include "dbad.inc";

      if (isset($_POST['terminal']))
      {
        $terminal = $_POST['terminal'];
      }

      echo ("Variables pasadas <br>");
      if (isset($_POST['sign']))
      {
        $sign = $_POST['sign'];
      }


      while ($post = each($_POST))
      {
      echo $post[0] . " = " . $post[1]."<br>";
      }


      echo ("----------------------------<br>");

      $sql = "Select * from datos where Terminal = '" . $terminal . "'";

      $result = mysqli_query ($conexion,$sql);

      if (mysqli_affected_rows($conexion)==0) {
        // Hay alguien intentando acceder al sistema?
        // Por que llega una peticion de un terminal que no existe?

	        echo "KO, No existe el terminal ".$terminal;
          die();
	    }
      else {
        echo "Encontrado registro<br>";
      }

      $row = mysqli_fetch_array($result);

      $sign = $row["Sign"];
      $lastopdate = $row['LastOpDate'];
      echo 'Sign : '.$sign."<br>";
      echo 'LastOpDate : '.$lastopdate;

      mysqli_free_result($result);

      /* cerrar la conexión */
      mysqli_close($conexion);

?>
