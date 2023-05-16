<?php

// Version 2.0 11/03/2023
// Añadidir Anotacion Manual en el diario de movimientos para poder incorporar notas propias


date_default_timezone_set('Europe/Madrid');
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

if ($username <> "Cafe Duetazze"){
    die();
}

$terminal = "";
if (isset($_GET["terminal"])){
  $terminal = $_GET["terminal"];
}
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
              aqui los condicionantes
            **************************************************************************************/
            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cierre Diferido";
            $f["css"] = "'background-color': '#f21d05','color':'white' ,'font-weight': 'bold'"; // css class name
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cierre en Oficina"; 
            $f["css"] = "'background-color': '#f21d05','color':'white' ,'font-weight': 'bold'"; // css class name
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cierre Caja";
            $f["css"] = "'background-color': '#f21d01','color':'white' ,'font-weight': 'bold' "; // css class name
            //$f["class"] = "CierreCaja"; 
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Descuadre de Contadores"; 
            $f["css"] = "'background-color': '#000000','color':'red' ,'font-weight': 'bold'"; // css class name
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Bonos Añadidos";
            //$f["class"] = "blue-row"; 
            $f["css"] = "'background-color': '#C88EEB','color':'white' ,'font-weight': 'bold'"; // css class name
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Bonos Consumidos"; 
            $f["class"] = "green-row"; 
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cambio de Bonos";
            $f["css"] = "'color':'#353632' ,'background-color':'#e9f797' ,'font-weight': 'bold'";
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Nombre  Establecido"; 
            $f["css"] = "'color':'#FFFFFF' ,'background-color':' #000080' ,'font-weight': 'bold'";
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cambio de Nombre";
            $f["css"] = "'color':'#FFFFFF' ,'background-color':'#8CAB45' ,'font-weight': 'bold'";
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Precio Establecido"; 
            $f["css"] = "'color':'#FFFFFF' ,'background-color':'#B07F3A' ,'font-weight': 'bold'";
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Cambio de Precio";
            $f["css"] = "'color':'#FFFFFF' ,'background-color':'#BA5B36' ,'font-weight': 'bold'";
            $f_conditions[] = $f;

            $f = array();
            $f["column"] = "Operacion";
            $f["op"] = "=";
            $f["value"] = "Reset - Unidad Puesta a 0";
             $f["css"] = "'background-color': '#206301','color':'white' ,'font-weight': 'bold'"; // css class name
            $f_conditions[] = $f;


            $g->set_conditional_css($f_conditions);

            /*************************************************************************************
              aqui las opciones del grid
            **************************************************************************************/

            $opt["width"] = "100vw";
            $opt["height"] = "68vh";
            //$opt["caption"] = "Movimientos de teminales ($username)";
            $opt["altRows"] = true;
            $opt["altclass"] = "myAltRowClass";
            $opt["hidegrid"] = false;
            $opt["rownumbers"] = true;
            $opt["rownumWidth"] = 40;
            $opt["sortname"] = 'Fecha';
            $opt["sortorder"] = "desc";
            $opt["autowidth"] = false;
            $opt["shrinkToFit"] = true;
            $opt["footerrow"] = true;
            $opt["rowNum"] = 20;
            $opt["persistsearch"] = false;
            $opt["toolbar"] = "Bottom";

            $grid["export"] = array("format"=>"pdf", "filename"=>"my-file", "heading"=>"Invoice Details", "orientation"=>"landscape", "paper"=>"a4");
            $grid["export"]["range"] = "filtered"; // or "all"
            $grid["export"]["paged"] = "1";

            $opt["search_options"]["tmplNames"] = array("Buscar Entre Fechas", "Importes");
            $opt["search_options"]["tmplFilters"] = array(
              array(
                "groupOp" => "AND",
                "rules" => array (
                        array("field"=>"Fecha", "op"=>"gt", "data"=>"Desde"),
                          array("field"=>"Fecha", "op"=>"lt", "data"=>"Hasta"),
                        )
                      ),
                      array(
                        "groupOp" => "AND",
                        "rules" => array (
                        array("field"=>"Importe", "op"=>"eq", "data"=>"5")
                        )
                      )
                    );


            $g->set_options($opt);
            if ($terminal == ""){
                $g->select_command = "SELECT journal.*, datos.shop from journal inner join datos on journal.terminal = datos.terminal";
            }
            else 
            {
              $g->select_command = "SELECT journal.*, datos.shop from journal inner join datos on journal.terminal = datos.terminal where journal.terminal = '$terminal'";
            }
            // set database table for CRUD operations
            $g->table = "journal";



            $g->set_actions(array(
                                    "add"=>false, // allow/disallow add
                                    "edit"=>true, // allow/disallow edit
                                    "delete"=>false, // allow/disallow delete
                                    "view"=>false, // allow/disallow view
                                    "refresh" => true, // show/hide refresh button
                                    "search" => "advance", // show single/multi field search condition (e.g. simple or advance)
                                    "autofilter" => true, // show/hide autofilter for search
                                    "export_excel"=>true,
                                    "rowactions"=>false

                                ) );

            /***************************************************************************
             Definimos Las Columnas
            ****************************************************************************/


            $col = array();
            $col["title"] = "Id";
            $col["name"] = "Id";
            $col["width"] = "50";
            $col["sortable"] = true;
            $col["align"] = "center";
            $col["hidden"] = true;

            $cols[] = $col;

            $col = array();
            $col["title"] = "Fecha";
            $col["name"] = "Fecha";
            $col["formatter"] = "datetime";
            $col["formatoptions"] = array("srcformat"=>'Y-m-d H:i:s',"newformat"=>'d/m/Y H:i:s',"opts" => array());
            $col["width"] = "90";
            $col["sortable"] = true;
            $col["align"] = "center";
            $col["stype"] = "daterange";
            $col["searchoptions"]["opts"] = array("initialText"=>"Buscar entre fechas...", "applyButtonText"=>"Aplicar", "cancelButtonText"=>"Cancelar", "dateFormat"=>"dd/mm/yy", "clearButtonText"=>"Borrar","presetRanges"=>"");
            $cols[] = $col;


            $col = array();
            $col["title"] = "Terminal";
            $col["name"] = "Terminal";
            $col["dbname"] = "journal.Terminal";
            $col["width"] = "50";
            $col["sortable"] = false;
            $col["link"] = "terminales.php?terminal={Terminal}";
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Establecimiento";
            $col["name"] = "Establecimiento";
            $col["dbname"] = "journal.Establecimiento";
            $col["width"] = "100";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Operacion";
            $col["name"] = "Operacion";
            $col["width"] = "90";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Descripcion";
            $col["name"] = "Descripcion";
            $col["width"] = "100";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Importe";
            $col["name"] = "Importe";
            $col["width"] = "45";
            $col["sortable"] = true;
            $col["align"] = "Right";
            $col["formatter"] = "currency";
            $col["formatoptions"] = array("prefix" => "",
                                "suffix" => ' €',
                                "thousandsSeparator" => ".",
                                "decimalSeparator" => ",",
                                "decimalPlaces" => 2);
            $cols[] = $col;

            $col = array();
            $col["title"] = "Creditos";
            $col["name"] = "Creditos";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Tot. Dosis A";
            $col["name"] = "TotalDosisA";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Tot. Dosis B";
            $col["name"] = "TotalDosisB";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;
            $col = array();
            $col["title"] = "Parc. Dosis A";
            $col["name"] = "ParcialDosisA";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Parc. Dosis B";
            $col["name"] = "ParcialDosisB";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;
            $col = array();
            $col["title"] = "Caja";
            $col["name"] = "Caja";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Notas";
            $col["name"] = "Notes";
            $col["width"] = "250";
            $col["sortable"] = false;
            $col["align"] = "left";
            $col["editable"] = true;
            $col["edittype"] = "textarea"; 
            $col["editoptions"] = array("rows"=>3, "cols"=>50);
            $cols[] = $col;



          // para los totales

          $col = array();
          $col["title"] = "total_Recargas";
          $col["name"] = "total_Recargas";
          $col["width"] = "40";
          $col["hidden"] = true;
          $cols[] = $col;

          $col = array();
          $col["title"] = "total_Cierres";
          $col["name"] = "total_Cierres";
          $col["width"] = "40";
          $col["hidden"] = true;
          $cols[] = $col;



$g->set_columns($cols);



// ****************************************************************************************************************************
// funciones calculo de totales
// ****************************************************************************************************************************

// running total calculation
$e = array();
$e["on_data_display"] = array("pre_render","",true);
$e["on_render_pdf"] = array("render_pdf", null, true);

$e["js_on_select_row"] = "grid_onselect";
$e["js_on_load_complete"] = "grid_onload";
//$e["js_on_delete_row"] = "grid_ondelete";
$g->set_events($e);




function pre_render($data)
{

	$rows = $_GET["jqgrid_page"] * $_GET["rows"];
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction

	// to where filtered data

  $swhere = "WHERE Operacion = 'Cierre Caja'"  .$_SESSION["jqgrid_list1_filter"];

	global $g;

	// running total
	$result = $g->execute_query("SELECT SUM(importe) as s FROM (SELECT importe FROM journal inner join datos on journal.terminal = datos.terminal $swhere ORDER BY $sidx $sord ) AS tmp");
	$rs = $result->GetRows();
	$rs = $rs[0];
	foreach($data["params"] as &$d)
	{
		$d["total_Cierres"] = abs($rs["s"]);
	}

  $swhere = "where journal.Operacion = 'Venta' "  .$_SESSION["jqgrid_list1_filter"]  ;

	// table total (with filter)
	$result = $g->execute_query("SELECT SUM(importe) as s FROM (SELECT importe FROM journal inner join datos on journal.terminal = datos.terminal $swhere) AS tmp");
	$rs = $result->GetRows();
	$rs = $rs[0];
	foreach($data["params"] as &$d)
	{
		//$d["total_Recargas"] = $rs["s"];
    $d["total_Recargas"] = $rs["s"];
	}
}

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



// ****************************************************************************************************************************

            // render grid and get html/js output
            $out = $g->render("list1");
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Incompatibilidad entre bootstrap y el selector de fechas datepicker
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    -->

		<link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css"></link>
		<link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css"></link>

		<script src="./lib/js/jquery.min.js" type="text/javascript"></script>
		<script src="./lib/js/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
		<script src="./lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="./lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
		<!-- these css and js files are required by php grid -->
    <link rel="stylesheet" href="./css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js" type="text/javascript"></script>
  	<link rel="stylesheet" type="text/css" media="screen" href="https://cdn.rawgit.com/tamble/jquery-ui-daterangepicker/0.5.0/jquery.comiseo.daterangepicker.css"></link>
  	<script src="https://cdn.rawgit.com/tamble/jquery-ui-daterangepicker/0.5.0/jquery.comiseo.daterangepicker.min.js" type="text/javascript"></script>



		<meta charset="utf-8">
		<title>Movimiento de terminales</title>


      <!--/*Para el Datepicker*/-->
      <script src="./resources/datepicker/datepicker-es.js"; type="text/javascript"></script>

      <!--*****************************************
       Busqueda por fechas
      *****************************************-->

      <script>
          jQuery(window).load(function() {
          jQuery(".datepicker").datetimepicker(
                      {
                      "disabled":false,
                      "dateFormat":"dd-mm-yy",
                      "changeMonth": true,
                      "changeYear": true,
                      "firstDay": 1,
                      "showOn":'both'
                      }
                    ).next('button').button({
                      icons: {
                        primary: 'ui-icon-calendar'
                      }, text:false
                    }).css({'font-size':'80%', 'margin-left':'2px', 'margin-top':'-5px'});

            });
          jQuery("#search_date").click(function() {

            grid = jQuery("#list1");

          // open initially hidden grid
          // $('.ui-jqgrid-titlebar-close').click();

            if (jQuery("#datefrom").val() == '' || jQuery("#dateto").val() == '')
              return false;

            var f = {groupOp:"AND",rules:[]};
            if (jQuery("#datefrom").val())
                f.rules.push({field:"Fecha",op:"ge",data:jQuery("#datefrom").val()});

            if (jQuery("#dateto").val())
                f.rules.push({field:"Fecha",op:"le",data:jQuery("#dateto").val()});

            var s = {groupOp:"OR",rules:[],groups:[f]};
            s.rules.push({field:"Fecha",op:"nu",data:''});

                grid[0].p.search = true;
                jQuery.extend(grid[0].p.postData,{filters:JSON.stringify(s)});

                grid.trigger("reloadGrid",[{jqgrid_page:1,current:true}]);
                return false;
          });
</script>

<style>
      /* increase font & row height */
      .ui-jqgrid *, .ui-widget, .ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-size:13px; }
      .ui-jqgrid tr.jqgrow td { height:12px; }

      /* big toolbar icons */
      .ui-pager-control .ui-icon, .ui-custom-icon { zoom: 125%; -moz-transform: scale(1.25); -webkit-zoom: 1.25; -ms-zoom: 1.25; }
      .ui-jqgrid .ui-jqgrid-pager .ui-pg-div span.ui-icon { margin: 0px 2px; }
      .ui-jqgrid .ui-jqgrid-pager { height: 14px; }
      .ui-jqgrid .ui-jqgrid-pager .ui-pg-div { line-height: 12px; }


</style>




<script>
//on select
//function grid_onselect(){
//  alert ("Fila Seleccionada");
//}

//function grid_ondelete(){
  //alert ("Fila Seleccionada para borrar");
//}

/***********************************************************************
Suma de totales
*************************************************************************/
          // e.g. to show footer summary
          function grid_onload()
          {

            var grid = $("#list1");

            // sum of displayed result
            sum = grid.jqGrid('getCol', 'Importe', false, 'sum'); // 'sum, 'avg', 'count' (use count-1 as it count footer row).

            // suma de importes de los cierres
            sum_Cierres = grid.jqGrid('getCol', 'total_Cierres')[0];

            // suma del total de las ventas
            sum_Recargas = grid.jqGrid('getCol', 'total_Recargas')[0];

            // numero de registros
            c = grid.jqGrid('getCol', 'Id', false, 'sum');

            // 4th arg value of false will disable the using of formatter
            grid.jqGrid('footerData','set', { Notes: 'Ventas : '+ Intl.NumberFormat('eu-EU', { style: 'currency', currency: 'EUR' }).format(sum_Recargas)+' / Cierres : '+ Intl.NumberFormat('eu-EU', { style: 'currency', currency: 'EUR' }).format(sum_Cierres)}, false);
          };

</script>

	</head>
	<body>
    <style>
        /* alternate row color */
        .myAltRowClass { background-color: #edf2f5 ; }



    </style>



        <footer id="pageFooter"><h6>By Knessen Korps S.L.</h6></footer>
        <logo id="pageLogo">
            COFFEE PAY
            <div class="logoc">
              <hr>
            </div>
        </logo>
        <header id="pageHeader">
            <p>Movimientos de teminales.</p>
            <!--<img src="../../images/kn.jpg">-->
        </header>
        <article id="mainArticle"><?php echo $out?></article>
        <nav id="mainNav">

          <p>Menú</p>
          <div id="menulinks">
            <a class="text-white" href="index2.php" >Movimientos</a>
            <a class="text-white" href="terminales.php">Terminales</a>
            <a class="text-white" href="usuarios.php">Usuarios</a>
          </div>

        </nav>

	</body>

</html>
