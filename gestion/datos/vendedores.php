<?php
// Gestion de Terminales 
// para login seguro
include_once '../../login/includes/db_connect.php';
include_once '../../login/includes/functions.php';

$Version = "3.04 22/05/2023";

date_default_timezone_set('Europe/Madrid');

sec_session_start();

if (login_check($mysqli) == false) {
  echo ("No tiene autorizacion para ver esta página");
  die();
}
$userterminal  = htmlentities($_SESSION['terminal']);
$username = htmlentities($_SESSION['username']);
$oldbonos = 0;
$selectedtermimal = "";
// si se pasa un perametro de terminal, se ve solo el terminal seleccionado
$terminal = "";
if (isset($_GET["terminal"])) {
  $terminal = $_GET["terminal"];
}


// -------------------------------------------------
include_once("./config.php");

// include and create object
include(PHPGRID_LIBPATH . "inc/jqgrid_dist.php");

$db_conf = array(
  "type"      => PHPGRID_DBTYPE,
  "server"    => PHPGRID_DBHOST,
  "user"      => PHPGRID_DBUSER,
  "password"  => PHPGRID_DBPASS,
  "database"  => PHPGRID_DBNAME
);

$g = new jqgrid($db_conf);




/*************************************************************************************
              aqui los condicionantes
 **************************************************************************************/
$opt = array(); // Si hace mas de 3 dias que no envia un Alive, lo indica
$opt["column"] = "Activo";
$opt["target"] = "Activo";
$opt["op"] = "=";
$opt["value"] = 0; // you can use placeholder of column name as value
//$f["cellcss"] = "'color':'red' ,'font-weight': 'bold'";
$opt["css"] = "'background-color':'white','color':'red','opacity':0.8";
$opt_conditions[] = $opt;


$g->set_conditional_css($opt_conditions);
/*************************************************************************************
              aqui las opciones del grid
 **************************************************************************************/
$opt["width"] = "100vw";
$opt["height"] = "68vh";
$opt["altRows"] = true;
$opt["altclass"] = "myAltRowClass";
$opt["hidegrid"] = false;
$opt["rownumbers"] = true;
$opt["rownumWidth"] = 40;
$opt["sortname"] = 'Id';
$opt["sortorder"] = "asc";
$opt["autowidth"] = true;
$opt["shrinkToFit"] = true;
$opt["footerrow"] = false;
$opt["rowNum"] = 5000;
$opt["persistsearch"] = false;
$opt["toolbar"] = "bottom";
$opt["edit_options"] = array('width' => '420');

$grid["export"] = array("format" => "pdf", "filename" => "my-file", "heading" => "Invoice Details", "orientation" => "landscape", "paper" => "a4");
$grid["export"]["range"] = "filtered"; // or "all"
$grid["export"]["paged"] = "1";


$g->set_options($opt);


$g->set_events($e);

if ($terminal == "") {
  $g->select_command = "SELECT * from vendedores";
}



// set database table for CRUD operations
$g->table = "vendedores";



$g->set_actions(array(
  "add" => true, // allow/disallow add
  "edit" => true, // allow/disallow edit
  "delete" => true, // allow/disallow delete
  "view" => false, // allow/disallow view
  "refresh" => true, // show/hide refresh button
  "search" => "advance", // show single/multi field search condition (e.g. simple or advance)
  "autofilter" => true, // show/hide autofilter for search
  "export_excel" => true
));

/***************************************************************************
             Definimos Las Columnas
 ****************************************************************************/



$col = array();
$col["title"] = "Código";
$col["name"] = "Codigo";
$col["width"] = "40";
$col["hidden"] = false;
$col["sortable"] = true;
$col["align"] = "center";
//$col["show"] = array("list" => true, "add" => true, "edit" => true, "view" => true, "bulkedit" => false);
$col["editable"] = true;

$col["editrules"]["readonly"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Usuario";
$col["name"] = "Usuario";
$col["editable"] = true;
$col["width"] = "100";
$col["sortable"] = false;
$col["align"] = "left";
$col["hidden"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Activo";
$col["name"] = "Activo";
$col["width"] = "40";
$col["sortable"] = false;
$col["align"] = "center";
$col["hidden"] = false;
$col["editable"] = true;

$cols[] = $col;

$col = array();
$col["title"] = "Acciones";
$col["name"] = "act";
$cols[] = $col;

$g->set_columns($cols);


//$e["on_upload"] = "grid_onupload";

$g->set_events($e);




// Registro de incidencias

function Reclog($StringToRecord)
{
  $myfile = fopen("Terminales.txt", "a") or die("Unable to open file!");
  fwrite($myfile, $StringToRecord);
  fwrite($myfile, PHP_EOL);
}


// ****************************************************************************************************************************

// render grid and get html/js output
$out = $g->render("list1");
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

  <link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css">
  </link>
  <link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css">
  </link>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <!-- Grid-->
  <script src="./lib/js/jquery.min.js" type="text/javascript"></script>
  <script src="./lib/js/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
  <script src="./lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
  <script src="./lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>

  <link rel="stylesheet" href="./css/estilos.css">
  <link rel="stylesheet" type="text/css" href="./css/terminales.css" />


  <!-- Bootstrap CSS -->


  <meta charset="utf-8">
  <title>Vendedores</title>

  <script type="text/javascript">
   
  </script>


</head>

<body>
  <script>

  </script>
  <header id="pageHeader">
    <p>
    <h6>Listado de Vendedores.</h6><br></p>

  </header>
  <logo id="pageLogo">
    CAFE DUETAZZE
    <div class="logoc">
      <hr>EMPRESA ANDALUZA</hr>
    </div>
  </logo>

  <article id="mainArticle"><?php echo $out ?></article>

  <article id="mainArticle"><?php echo $out ?></article>
  <nav id="mainNav">
    <p>Menú</p>
    <div id="menulinks">
      <a class="text-white" href="index2.php">Movimientos</a>
      <a class="text-white" href="terminales.php">Terminales</a>
      <a class="text-white" href="../../graph/estadisticas/">Estadísticas</a>
      <a class="text-white" href="usuarios.php">Usuarios</a>
      <a class="text-white" href="vendedores.php">Vendedores</a>
    </div>

  </nav>

  <footer id="pageFooter">
    <h6>By Knessen Korps S.L.</h6>
  </footer>
 
</body>

</html>