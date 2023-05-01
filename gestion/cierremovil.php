<?php
/*******************************************************************************
      cierremovil.php
      Descripcion         : Servicio que permite a los comerciales cerrar la caja desde el movil
      Fecha Inicio        : 20/04/2023
      Fecha modificacion  : 20/04/2023
      Version             : 1.1
      Author              : Pedro Couto
********************************************************************************/

    date_default_timezone_set('Europe/Madrid');

      //Importamos los ficheros de geolocalizacion
      require('getip.php');
      // Captura la ip de la peticion
      require_once('./lib/geoplugin.class.php');    // Geolocaliza la ip
      // y los de la base de datos

      include ('conexion.inc');

 
       
      $terminalfound = false;
      $notas  = "";
      if (isset($_POST["notas"])){
          $notas =$_POST["notas"];
      }

      echo ("<script>alert 'hola';</script>");

      $importe = 0;
      if (isset($_POST["importe"])){
              $importe =$_POST["importe"];
      }
      if ($importe == "") { $importe =0 ;} // evita el error en la funcion ready de javascript, ya que si importe vale "" la funcion no funciona

      if (isset($_POST["terminal"])){

          $terminal = $_POST["terminal"];

          $sql = "select * from journal where Terminal  = '$terminal' and Operacion = 'Venta' order by Fecha desc limit 1";
          //echo $sql;
          $Result = mysqli_query($conexion, $sql);
          if (!$Result)
          {
              die("error en sql". mysqli_error($conexion));
          }
          
          $caja = 0;
          if (mysqli_affected_rows($conexion)==0){
             echo "<span style ='color:#e31717; background-color: #74992e;);'><h3>Terminal <b>$terminal</b> no enontrado</h3></span>";
          }
      
          else 
          {
              $Row = mysqli_fetch_array( $Result);
              $caja  =$Row["Caja"];
              echo ("<span style='color:#302c9b;text-align:center;'>Establecimiento <b>".$Row['Establecimiento']."<br><h2></b> Caja registrada : <b>".$caja. ".00 €</b></h2></span>");
              $terminalfound = true;

          }

        if ($caja <>$importe && $terminalfound && $importe > 0){
            echo ("<script>alert ('La caja ( ".$caja.",00 € ), no coincide con el importe insertado ( ".$importe.",00€ )');</script>");
        }

          if ($terminalfound && $importe > 0 ){
                    
             echo ("
                  <script>

                  if (confirm('Confirma el cierre de Caja?') == true) {

                      
                      data = new FormData();
                      data.set('terminal','".$terminal."');
                      data.set('importe','".$importe."');
                      data.set('notas','".$notas."');
                      data.set ('cmd','cierre');  
                      let request = new XMLHttpRequest();
                      request.open('POST', './catch.php', true);
                      request.send(data);
                      
                      alert('Cierre de Caja Realizado Correctamente!');
                      window.location.replace('./cierremovil.php');
                  }
                  
            
                                 
                  </script>");

              }


        }
      

      

?>


<!DOCTYPE html>
<html lang="en" style="text-align: center;">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>duetazze comerciales</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


  <script>
    $(document).ready(function() {

        vterminal = <?php echo ($terminal)?>;
        vimporte = <?php echo ($importe)?>;
        document.getElementById("terminal").value = vterminal;
        document.getElementById("importe").value = vimporte;
        if (vimporte == 0){
            document.getElementById("importe").value = "";
            document.getElementById("importe").focus();
        }
        else{


            document.getElementById("notas").focus();
        }
        
            
            });
  </script>


</head>

<body class="text-center">


  <div class="container" >
      <div class="text-center">
          <div  style="color:#0d6ef; background:  #0d6efd;border-radius: -3px;border: 10px none #6610f2;margin-top: 24px;box-shadow: 0px 4px 6px  #0a0a0a;">
              <p style="margin: 9px;color: #dee2e6;;text-align: center;font-weight: bold;font-size: 19px;" >Caffeé Duetazze</p>

          </div>
          <div style="color: var(--bs-gray-600);border-radius: -17px;border: 10px none var(--bs-indigo);margin-top: 23px;text-align: center;">
              <p  id = "cartel" style="margin: 0px;color: #878787;text-align: center;font-weight: bold;font-size: 20px;background: #e6e6e6;border-radius: -2px;height: 33.5px;border-width: 0px;border-color: #0a0a0a;width: 100%;box-shadow: 0px 2px 17px;">Cierre</p>
              <form action ="" method = "POST" autocomplete="off">
                  <div class="table-responsive" style="margin-top: 22px;">
                      <table class="table">
                          <thead style="margin-top: 4px;">
                              <tr style="margin-top: 0px;">
                                  <th style="text-align: left;margin-left: 16px;"><label class="form-label" style="text-align: left;font-weight: bold;margin-top: 7px;margin-left: 16px;">Terminal</label></th>
                                  <th><input class="form-control"  type="text" id= "terminal" name="terminal" style="margin-left: -19px;border-width: 1px;width: 200px;" placeholder="Numero de Terminal" autofocus="" inputmode="numeric" minlength="5" maxlength="5" ></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr style="margin-top: 0px;">
                                  <td style="text-align: left;margin-top: 0px;"><label class="form-label" style="text-align: left;font-weight: bold;margin-top: 7px;margin-left: 16px;">Importe&nbsp;&nbsp;</label></td>
                                  <td><input class="form-control" type="text" id = "importe" name="importe" style="margin: 0px;margin-left: -19px;border-width: 1px;margin-top: 14px;width: 200px;" placeholder="Importe" autofocus="" ></td>
                              </tr>
                              <tr>
                                  <td style="text-align: left;"><label class="form-label" style="text-align: center;font-weight: bold;margin-left: 16px;margin-top: 7px;">Notas&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label></td>
                                  <td><textarea id = "notas" name= "notas" class="form-control" style="width: 200px;margin-top: 13px;height: 94px;margin-left: -19px;"></textarea></td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                  <button class="btn btn-primary" type="submit" style="margin-top: 17px;width: 193.5px;box-shadow: 3px 4px 6px rgb(57,56,56);">Enviar</button>
              </form>
          </div>
      </div>
  </div>
  
</body>

</html>
