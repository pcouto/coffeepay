<?php

date_default_timezone_set('Europe/Madrid');
// para login seguro
include_once '../../login/includes/db_connect.php';
include_once '../../login/includes/functions.php';

sec_session_start();

if (login_check($mysqli) == false){
    echo ("No tiene autorizacion para ver esta página");
    die();
}
$terminal  = htmlentities($_SESSION['terminal']);
$username = htmlentities($_SESSION['username']);

//echo ("Usuario : ". $username);

if ($username == "Cafe Duetazze"){
    header('Location: ./index2.php');
    die();
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
            $f["column"] = "AnswerCode";
            $f["op"] = ">";
            $f["value"] = 0; // you can use placeholder of column name as value
            //$f["cellcss"] = "'color':'red' ,'font-weight': 'bold'";
            $f["class"] = "red-row"; // css class name
            $f_conditions[] = $f;

            $g->set_conditional_css($f_conditions);


            /*************************************************************************************
              aqui las opciones del grid
            **************************************************************************************/
            $opt["height"] = "90%";
            $opt["width"] = 800;
            $opt["caption"] = "Resumen de recargas teminales ($username)";
            $opt["hidegrid"] = false;
            $opt["rownumbers"] = true;
            $opt["rownumWidth"] = 40;
            $opt["sortname"] = 'Fecha';
            $opt["sortorder"] = "desc";
            $opt["autowidth"] = true;
            $opt["shrinkToFit"] = true;
            $opt["footerrow"] = true;
            $opt["rowNum"] = 18;
            $opt["persistsearch"] = false;
            $opt["toolbar"] = "bottom";

            $grid["export"] = array("format"=>"pdf", "filename"=>"my-file", "heading"=>"Invoice Details", "orientation"=>"landscape", "paper"=>"a4");
            $grid["export"]["range"] = "filtered"; // or "all"
            $grid["export"]["paged"] = "1";



            $opt["search_options"]["tmplNames"] = array("Buscar Entre Fechas", "Importes");
            $opt["search_options"]["tmplFilters"] = array(
              array(
                "groupOp" => "AND",
                "rules" => array (
                        array("field"=>"Date", "op"=>"gt", "data"=>"Desde"),
                          array("field"=>"Date", "op"=>"lt", "data"=>"Hasta"),
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

            //$g->select_command = "SELECT * from journal where Date between '$xdatefrom' and '$xdateto'";
            $g->select_command = "SELECT journal.*, datos.shop from journal inner join datos on journal.terminal = datos.terminal  where journal.terminal = '$terminal'";

            // set database table for CRUD operations
            $g->table = "journal";



            $g->set_actions(array(


                                    "add"=>false, // allow/disallow add
                                    "edit"=>false, // allow/disallow edit
                                    "delete"=>false, // allow/disallow delete
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
            $col["title"] = "Action";
            $col["name"] = "act";
            $col["width"] = "10";
            $col["hidden"] = true;
            $cols[] = $col;

            $col = array();
            $col["title"] = "Id";
            $col["name"] = "Id";
            $col["width"] = "50";
            $col["sortable"] = true;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Fecha";
            $col["name"] = "Fecha";
            $col["formatter"] = "datetime";
            $col["formatoptions"] = array("srcformat"=>'Y-m-d H:i:s',"newformat"=>'d/m/Y H:i:s',"opts" => array());
            $col["width"] = "135";
            $col["sortable"] = true;
            $col["align"] = "center";
            $col["stype"] = "daterange";
            $col["searchoptions"]["opts"] = array("initialText"=>"Buscar entre fechas...", "applyButtonText"=>"Aplicar", "cancelButtonText"=>"Cancelar", "dateFormat"=>"dd/mm/yy", "clearButtonText"=>"Borrar","presetRanges"=>"");
            $cols[] = $col;


            $col = array();
            $col["title"] = "Terminal";
            $col["name"] = "Terminal";
            $col["width"] = "50";
            $col["sortable"] = false;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Operacion";
            $col["name"] = "Operaccion";
            $col["width"] = "80";
            $col["sortable"] = false;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Descripcion";
            $col["name"] = "Descripcion";
            $col["width"] = "100";
            $col["sortable"] = false;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Importe";
            $col["name"] = "Importe";
            $col["width"] = "40";
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
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Tot. Dosis A";
            $col["name"] = "TotDosisA";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Tot. Dosis B";
            $col["name"] = "TotDosisB";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Caja";
            $col["name"] = "Caja";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Notas";
            $col["name"] = "Notes";
            $col["width"] = "250";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;

// para los totales
// virtual column for running total
$col = array();
$col["title"] = "total_errores";
$col["name"] = "total_errores";
$col["width"] = "1";
$col["hidden"] = true;
$cols[] = $col;

// virtual column for grand total
$col = array();
$col["title"] = "total_Recargas";
$col["name"] = "total_Recargas";
$col["width"] = "1";
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

$g->set_events($e);



function pre_render($data)
{
	$rows = $_GET["jqgrid_page"] * $_GET["rows"];
	$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
	$sord = $_GET['sord']; // get the direction

	// to where filtered data
  global $terminal;
  $swhere = "WHERE Operacion = 'Error' and terminal = '$terminal' "  .$_SESSION["jqgrid_list1_filter"];
	global $g;


	// running total
	$result = $g->execute_query("SELECT SUM(importe) as s FROM (SELECT importe FROM journal  $swhere ORDER BY $sidx $sord ) AS tmp");
	$rs = $result->GetRows();
	$rs = $rs[0];
	foreach($data["params"] as &$d)
	{
		$d["total_errores"] = $rs["s"];
	}

  $swhere = "WHERE Operacion = 'Sale'  and terminal = '$terminal' "  .$_SESSION["jqgrid_list1_filter"]  ;

	// table total (with filter)
	$result = $g->execute_query("SELECT SUM(importe) as s FROM (SELECT importe FROM journal  $swhere) AS tmp");
	$rs = $result->GetRows();
	$rs = $rs[0];
	foreach($data["params"] as &$d)
	{
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

		<link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css"></link>
		<link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css"></link>

		<script src="./lib/js/jquery.min.js" type="text/javascript"></script>
		<script src="./lib/js/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
		<script src="./lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="./lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
		<!-- these css and js files are required by php grid -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js" type="text/javascript"></script>
  	<link rel="stylesheet" type="text/css" media="screen" href="https://cdn.rawgit.com/tamble/jquery-ui-daterangepicker/0.5.0/jquery.comiseo.daterangepicker.css"></link>
  	<script src="https://cdn.rawgit.com/tamble/jquery-ui-daterangepicker/0.5.0/jquery.comiseo.daterangepicker.min.js" type="text/javascript"></script>


		<meta charset="utf-8">
		<title></title>

    <style>
      .ui-jqgrid {font-weight: bold;font-size:12px; font-family: Arial, Helvetica, sans-serif; }
      .ui-jqgrid tr.jqgrow td {height: 20px; padding:1 5px;}
    </style>

    <style>
    	tr.red-row
    	{
        font-size: 12px;
    		/*background: #faf8f2;*/
    		color: red;
    	}

      .footrow td {
        font-size: 14px;
        background-color: #BEBEBE;
        border-style: hidden;
        text-align: right;
        color: #FFFFFF;
      }


    	</style>

      <!--/*Para el Datepicker*/-->
      <script src="./resources/datepicker/datepicker-es.js"; type="text/javascript"></script>
	</head>
	<body>


      <div style="clear:both;margin-bottom:10px">
        <img src="./images/logo.png"  width="100" height="70">

      </div>

      <div style="clear:both;margin-bottom:10px"></div>
      </div>

        <div style="margin:10px;">
      		<!-- display grid here -->
      		<?php echo $out?>
      		<!-- display grid here -->
    		</div>
      <br>

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
                      f.rules.push({field:"Date",op:"ge",data:jQuery("#datefrom").val()});

              		if (jQuery("#dateto").val())
                      f.rules.push({field:"Date",op:"le",data:jQuery("#dateto").val()});

              		var s = {groupOp:"OR",rules:[],groups:[f]};
              		s.rules.push({field:"Date",op:"nu",data:''});

                      grid[0].p.search = true;
                      jQuery.extend(grid[0].p.postData,{filters:JSON.stringify(s)});

                      grid.trigger("reloadGrid",[{jqgrid_page:1,current:true}]);
                      return false;
                });
            	</script>

        <!--***********************************************************************
        // Suma de totales
        //*************************************************************************-->

              <script>
              	// e.g. to show footer summary
              	function grid_onload()
              	{
              		var grid = $("#list1");

              		// sum of displayed result
              		sum = grid.jqGrid('getCol', 'Importe', false, 'sum'); // 'sum, 'avg', 'count' (use count-1 as it count footer row).

              		// suma de importes de los errores
              		//sum_errores = grid.jqGrid('getCol', 'total_errores')[0];

              		// suma del total de las recargas
              		sum_recargas = grid.jqGrid('getCol', 'total_Recargas')[0];

              		// numero de registros
              		c = grid.jqGrid('getCol', 'Id', false, 'sum');

              		// 4th arg value of false will disable the using of formatter
                  grid.jqGrid('footerData','set', { Notes: 'Total Importes : '+sum_recargas+' €'}, false);
              	};

              	// e.g. to update footer summary on selection

                function grid_onselect()
              	{

              		var grid = $("#list1");

              		var t = 0;
              		var selr = grid.jqGrid('getGridParam','selarrrow'); // array of id's of the selected rows when multiselect options is true. Empty array if not selection
              		for (var x=0;x<selr.length;x++)
              		{
              			t += parseFloat(grid.jqGrid('getCell', selr[x], 'total'));
              		}
              		grid.jqGrid('footerData','set', {Fecha: 'Selected Total: '+t.toFixed(2)}, false);
              	};

              	</script>
	</body>
</html>
