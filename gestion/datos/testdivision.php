<?php
include_once("./config.php");



// include and create object
include(PHPGRID_LIBPATH."inc/jqgrid_dist.php");

  $db_conf = array(
                      "type"      => PHPGRID_DBTYPE,
                      "server"    => PHPGRID_DBHOST,
                      "user"      => PHPGRID_DBUSER,
                      "password"  => PHPGRID_DBPASS,
                      "database"  => PHPGRID_DBNAME
                  );

  $g = new jqgrid($db_conf);
  $opt["caption"] = "Datos Historico de Terminales.";
  $opt["height"] = "90%";
  $g->set_options($opt);

  $g->select_command = "SELECT journal.*, datos.shop from journal inner join datos on journal.terminal = datos.terminal";

  // set database table for CRUD operations
  $g->table = "journal";

  $out = $g->render("list1");

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Division de la pantalla en css</title>
    <link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css"></link>
    <link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css"></link>

    <script src="./lib/js/jquery.min.js" type="text/javascript"></script>
    <script src="./lib/js/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="./lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="./lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="./css/estilos.css">


  </head>

  <body>
    <section id="Pantalla">

      <div class="menu">
        <h1>Menu Principal</h1>
        <p>Parrafo de demostración de menu</p>
        <a href="">Aprender Css</a>
      </div>


      <div class="contenido">

        <?php echo $out?>
      </div>


    </section>

  </body>
</html>
