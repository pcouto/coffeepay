<?php
// para login seguro
include_once '../../login/includes/db_connect.php';
include_once '../../login/includes/functions.php';

sec_session_start();

if (login_check($mysqli) == false){
    echo ("No tiene autorizacion para ver esta página");
    die();
}
$userterminal  = htmlentities($_SESSION['terminal']);
$username = htmlentities($_SESSION['username']);

// si se pasa un perametro de terminal, se ve solo el terminal seleccionado



// -------------------------------------------------
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




            /*************************************************************************************
              aqui las opciones del grid
            **************************************************************************************/
            $opt["height"] = "90%";
            $opt["width"] = 800;
            $opt["caption"] = "Listado de Usuarios ($username)";
            $opt["altRows"] = true;
            $opt["altclass"] = "myAltRowClass";
            $opt["hidegrid"] = false;
            $opt["rownumbers"] = true;
            $opt["rownumWidth"] = 40;
            $opt["sortname"] = 'Usuario';
            $opt["sortorder"] = "asc";
            $opt["autowidth"] = true;
            $opt["shrinkToFit"] = true;
            $opt["footerrow"] = true;
            $opt["rowNum"] = 18;
            $opt["persistsearch"] = false;
            $opt["toolbar"] = "bottom";
            $opt["edit_options"] = array('width'=>'620');


            $g->set_options($opt);

            $g->set_events($e);

              $g->select_command = "SELECT * from usuarios ";



            // set database table for CRUD operations
            $g->table = "usuarios";



            $g->set_actions(array(
                                    "add"=>true, // allow/disallow add
                                    "edit"=>true, // allow/disallow edit
                                    "delete"=>true, // allow/disallow delete
                                    "view"=>false, // allow/disallow view
                                    "refresh" => true, // show/hide refresh button
                                    "search" => "advance", // show single/multi field search condition (e.g. simple or advance)
                                    "autofilter" => true, // show/hide autofilter for search
                                    "export_excel"=>true

                                ) );

            /***************************************************************************
             Definimos Las Columnas
            ****************************************************************************/
            $col = array();
            $col["title"] = "Id";
            $col["name"] = "Id";
            $col["editable"] = false  ;
            $col["width"] = "50";
            $col["sortable"] = true;
            $col["align"] = "left";
            $cols[] = $col;
        

            $col = array();
            $col["title"] = "Usuario";
            $col["name"] = "Usuario";
            $col["editable"] = true;
            $col["width"] = "50";
            $col["sortable"] = true;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Passwd";
            $col["name"] = "Passwd";
            $col["width"] = "50";
            $col["sortable"] = false;
            $col["editable"] = true;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Nombre";
            $col["name"] = "Nombre";
            $col["width"] = "25";
            $col["sortable"] = true;
            $col["editable"] = true;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Telefono";
            $col["name"] = "Telefono";
            $col["width"] = "15";
            $col["sortable"] = false;
            $col["editable"] = true;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Correo";
            $col["name"] = "Correo";
            $col["width"] = "50";
            $col["sortable"] = false;
            $col["align"] = "left";
            $col["editable"] = true;
            $col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>true);
            $col["editrules"]["readonly"] = false;
            $cols[] = $col;



            $col = array();
            $col["title"] = "Saldo";
            $col["name"] = "Saldo";
            $col["width"] = "12";
            $col["sortable"] = true;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Alta";
            $col["name"] = "FechaAlta";
            $col["formatter"] = "datetime";
            $col["formatoptions"] = array("srcformat"=>'Y-m-d H:i:s',"newformat"=>'d/m/Y H:i:s',"opts" => array());
            $col["width"] = "25";
            $col["hidden"] = false;
            $cols[] = $col;

            $col = array();
            $col["title"] = "Estado";
            $col["name"] = "Estado";
            $col["width"] = "4";
            $col["sortable"] = true;
            $col["editable"] = true;
            $col["align"] = "right";
            $cols[] = $col;

            $g->set_columns($cols);


            $e["on_upload"] = "grid_onupload";
            $g->set_events($e);


// custom on_export callback function
function render_pdf($param)
{
	$grid = $param["grid"];
	$arr = $param["data"];

	$html .= "<h1>".$grid->options["export"]["heading"]."</h1>";
	$html .= '<table border="0" cellpadding="4" cellspacing="2">';

	$i = 0;
	$total = 0;
	foreach($arr as $v)
	{
		$shade = ($i++ % 2) ? 'bgcolor="#efefef"' : '';
		$html .= "<tr>";
		foreach($v as $k=>$d)
		{
			if ($k == 'total')
				$total += floatval($d);

			// bold header
			if  ($i == 1)
				$html .= "<td bgcolor=\"lightgrey\"><strong>".ucwords($d)."</strong></td>";
			else
				$html .= "<td $shade>$d</td>";
		}
		$html .= "</tr>";
	}


	$html .= "<tr bgcolor=\"lightgrey\"><td></td><td></td><td align='right'><strong>Total: $total</strong></td><td></td><td></td></tr>";

	$html .= "</table>";

	return $html;
}

function update_terminal($data)
{
      $term = $data["Terminal"];
      $est =  $data["params"]["Establecimiento"];
      $bon =  $data["params"]["Bonos"];
      $fec =  $data["Fecha"];
    	global  $g;
      global $username;
      global $oldbonos;
      $str = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('".date('Y-m-d H:i:s')."','$term', '$est','Bonos','Añadidos Bonos : $bon/$oldbonos',0,'Usuario : $username')";
      $oldbonos = $bon;
      $result = $g->execute_query($str);
}
function select_terminal($data)
{
    global  $g;
    global $oldbonos;
    $term = $data["Terminal"];
    $oldbonos =  $data["params"]["Bonos"];
    $str = "'$data')";
    //$str = "Update datos set city = 'hello' where terminal  = '$term'";
   //echo ("<script>alert ('$str')</script>");
}
// ****************************************************************************************************************************

            // render grid and get html/js output
            $out = $g->render("list1");
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

    <style>
  /* increase font & row height */
      .ui-jqgrid *, .ui-widget, .ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-size:13px; }
      .ui-jqgrid tr.jqgrow td { height:12px; }
    </style>

<link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css"></link>
  	<link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css"></link>

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
    <link rel="stylesheet" type="text/css" href="./css/terminales.css"  />

 
    <!-- Bootstrap CSS -->
		<meta charset="utf-8">
		<title></title>

    <script type="text/javascript">
        function grid_onupload(){
          alert ("Cargado");
        }
    </script>

	</head>
	<body>
	<style>
	/* alternate row color */
    .myAltRowClass { background-color: #F1F1F1 !important; background-image: url('') !important; }


	</style>
    <footer id="pageFooter">Pie</footer>
    <header id="pageHeader">
          <p>Listado de Usuarios.</p>
    </header>
    <logo id="pageLogo">
        CAFE DUETAZZE
        <div class="logoc">
          <hr>EMPRESA ANDALUZA
        </div>
    </logo>

    <article id="mainArticle"><?php echo $out?></article>
    <nav id="mainNav">
      <p>Menú</p>
      <div id="menulinks">
        <a class="text-white" href="index2.php" >Movimientos</a>
        <a class="text-white" href="terminales.php">Terminales</a>
        <a class="text-white" href="usuarios.php">Usuarios</a>
      </div>

    </nav>
    <footer id="pageFooter"><h6>By Knessen Korps S.L.</h6></footer>
	</body>
</html>
