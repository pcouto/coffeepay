<?php

      /* Terminales.php

        Version 2.1 08/03/2023 20:56
          
      */

      // para login seguro
      include_once '../../login/includes/db_connect.php';
      include_once '../../login/includes/functions.php';

      date_default_timezone_set('Europe/Madrid'); 


      function Reclog($StringToRecord){
        $myfile = fopen("terminales.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $StringToRecord);
        fwrite($myfile, PHP_EOL);
      }

      sec_session_start();

      if (login_check($mysqli) == false){
          echo ("No tiene autorizacion para ver esta página");
          die();
      }
      $userterminal  = htmlentities($_SESSION['terminal']);
      $username = htmlentities($_SESSION['username']);
      $oldbonos = 0;
      $selectedtermimal= "";
      // si se pasa un perametro de terminal, se ve solo el terminal seleccionado
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
            $opt = array(); // Si hace mas de 3 dias que no envia un Alive, lo indica
            $opt["column"] = "Diff";
            $opt["target"] = "Alive";
            $opt["op"] = ">";
            $opt["value"] = 3; // you can use placeholder of column name as value
            //$f["cellcss"] = "'color':'red' ,'font-weight': 'bold'";
            $opt["cellcss"] = "'background-color':'#ff851b','color':'white','fontWeight':'bold','opacity':0.4"; 
            $opt_conditions[] = $opt;

            $opt = array(); // Si el terminal se ha inhabilitado, lo indica
            $opt["column"] = "Estado";
            $opt["target"] = "Terminal";
            $opt["op"] = "=";
            $opt["value"] = 0; // you can use placeholder of column name as value
            //$opt["class"] = "canceled_row"; // css class name
            $opt["css"] = "'background-color':'red','color':'white','fontWeight':'bold'"; // must use (single quote ') with css attr and value
            
            $opt_conditions[] = $opt;

            $opt = array(); // Si tiene bonos pendientes, lo tiene en 
            $opt["column"] = "Bonos";
            $opt["op"] = ">";
            $opt["value"] = 0; // you can use placeholder of column name as value
            $opt["cellcss"] = "'background-color':'#32a852','color':'white','fontWeight':'bold','opacity':0.4"; 
            $opt_conditions[] = $opt;


            $opt = array(); // 
            $opt["column"] = "MoreIcons";
            $opt["op"] = "<>";
            $opt["value"] = "99999"; // you can use placeholder of column name as value
            $opt["cellcss"] = "'background-color':'#32a852','color':'white','fontWeight':'bold','opacity':0.4"; 
            $opt_conditions[] = $opt;

            $g->set_conditional_css($opt_conditions);
            /*************************************************************************************
              aqui las opciones del grid
            **************************************************************************************/
            $opt["width"] = "100vw";
            $opt["height"] = "68vh";
            //$opt["caption"] = "Listado de Terminales ($username)";
            $opt["altRows"] = true;
            $opt["altclass"] = "myAltRowClass";
            $opt["hidegrid"] = false;
            $opt["rownumbers"] = true;
            $opt["rownumWidth"] = 40;
            $opt["sortname"] = 'Terminal';
            $opt["sortorder"] = "asc";
            $opt["autowidth"] = true;
            $opt["shrinkToFit"] = true;
            $opt["footerrow"] = true;
            $opt["rowNum"] =20;
            $opt["persistsearch"] = false;
            $opt["toolbar"] = "bottom";
            $opt["edit_options"] = array('width'=>'420');

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

            $e["on_update"] = array("update_terminal", null, true);
            //$e["js_on_select_row"] = "select_row";
            $e["on_data_display"] = array("filter_display","",true);
            $e["on_select"] = array("select_terminal", ""); 
            $e["on_upload"] = "grid_onupload";

            $g->set_events($e);

            if ($terminal == ""){
              $g->select_command = "SELECT * from datos";
            }
            else {
              $g->select_command = "SELECT * from datos where terminal = '".$terminal."'";
            }


            // set database table for CRUD operations
            $g->table = "datos";



            $g->set_actions(array(
                                    "add"=>false, // allow/disallow add
                                    "edit"=>true, // allow/disallow edit
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


            $col = array();
            $col["title"] = "Terminal";
            $col["name"] = "Terminal";
            $col["width"] = "15";
            $col["hidden"] = false;
            $col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
            $col["editrules"]["readonly"] = true;
            $cols[] = $col;

            $col["title"] = "Establecimiento";
            $col["name"] = "Establecimiento";
            $col["width"] = "40";
            $col["hidden"] = false;
            $col["sortable"] = true;
            $col["align"] = "left";
            $col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
            $col["editable"] = true;
            $col["editrules"]["readonly"] = false;
            $cols[] = $col;
            $col = array();
            $col["title"] = "Usuario";
            $col["name"] = "Username";
            $col["editable"] = false;
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "left";
            $cols[] = $col;


            $col = array();
            $col["title"] = "Password";
            $col["name"] = "Password";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Email";
            $col["name"] = "Email";
            $col["width"] = "40";
            $col["sortable"] = false;
            $col["align"] = "left";
            $col["editable"] = true;
            $col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
            $col["editrules"]["readonly"] = false;
            $cols[] = $col;

            $col = array();
            $col["title"] = "Telefono";
            $col["name"] = "telefono";
            $col["width"] = "25";
            $col["sortable"] = false;
            $col["editable"] = true;
            $col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "PPD";
            $col["name"] = "PrecioPorDosis";
            $col["width"] = "12";
            $col["sortable"] = true;
            $col["editable"] = true;
            $col["align"] = "right";

            $cols[] = $col;

            $col = array();
            $col["title"] = "Saldo";
            $col["name"] = "Saldo";
            $col["width"] = "12";
            $col["sortable"] = true;

            $col["align"] = "center";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Bonos";
            $col["name"] = "Bonos";
            $col["width"] = "12";
            $col["sortable"] = true;
            $col["editable"] = true;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Alive";
            $col["name"] = "Alive";
            $col["formatter"] = "datetime";
            $col["formatoptions"] = array("srcformat"=>'Y-m-d H:i:s',"newformat"=>'d/m/Y H:i:s',"opts" => array());
            $col["width"] = "28";
            $col["align"] = "right";
            $col["hidden"] = false;
            $cols[] = $col;

            $col = array();
            $col["title"] = "Dias S/C";
            $col["name"] = "Diff";
            $col["width"] = "12";
            $col["hidden"] = false;
            $col["align"] = "right";
            $cols[] = $col;

            $col = array();
            $col["title"] = "Estado";
            $col["name"] = "Estado";
            $col["width"] = "2";
            $col["hidden"] = true;
            $cols[] = $col;

            $col["default"] = $buttons_html;
            $cols[] = $col;

            $col = array();
            $col["title"] = "Actions";
            $col["name"] = "act";
            $cols[] = $col;




            $g->set_columns($cols);


            


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

// Miramos las diferencias de fechas para mostrar los terminales que llevan mas de 5 dias sin conectarse 

function filter_display($data)
{
 foreach($data["params"] as &$d)
  {
    $now = time(); // or your date as well
    $your_date = strtotime($d["Alive"]);
    $datediff = $now - $your_date;
    if ($d["Terminal"]<>"000001"){
      $d["Diff"] = intval($datediff/86400);
    }
  }
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

      reclog ("..................................................");
      reclog ($term);
      reclog ($est);
      reclog ($bon);
      reclog ($fec);

      $desc = '';
      if ($bon <> '0' ){
        $str = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('".date('Y-m-d H:i:s')."','$term', '$est','Bonos Añadidos','$bon Bonos',0,'Usuario : $username')";
        $oldbonos = $bon;
        $result = $g->execute_query($str);
      }

  }

function select_terminal($d)
  {    


    
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
		<title>Gestión de Terminales</title>




	</head>
	<body>

    <header id="pageHeader">
          <p>Listado de terminales.</p>
    </header>
    <logo id="pageLogo">
        CAFE DUETAZZE
        <div class="logoc">
          <hr>EMPRESA ANDALUZA</hr>
        </div>
    </logo>

    <article id="mainArticle"><?php echo $out?></article>
    <nav id="mainNav">
      <p>Menú</p>
      <div class = "col" id="menulinks">
        <a class="text-white" href="index2.php" >Movimientos</a>
        <a class="text-white" href="terminales.php">Terminales</a>
        <div class="col-md-8 text-center  role='group'">
            <button type="button" class="text-white btn btn-cierre btn-custom" onclick = "showModal('cierre')">Cierre de Caja</button>
            <button type="button" id = "showmodalcierre" class="text-white btn btn-cierre btn-custom" hidden data-toggle="modal" data-target="#Mcierre">Cierre de Caja</button>

            <button type="button" class="text-white btn btn-reset btn-custom"  onclick = "showModal('reset')">Reset</button>
            <button type="button" id = "showmodalreset" class="text-white btn btn-reset btn-custom" hidden  data-toggle="modal" data-target="#Mreset">Reset</button>

            <button type="button" class="text-white btn btn-cancel btn-custom"  onclick = "showModal('cancel')">Inhabilitar</button>
            <button type="button" id = "showmodalcancel" class="text-white btn btn-cancel btn-custom" hidden  data-toggle="modal" data-target="#Mcancel">Inhabilitar</button>
 
          </div>
       <a><a>
       <a class="text-white" href="usuarios.php">Usuarios</a>
      </div>

    </nav>
    <footer id="pageFooter"><h6>By Knessen Korps S.L.</h6></footer>
    <?php
       include "./Mreset.html";
       include "./Mcierre.html";
       include "./Mcancel.html";
    ?>

        <script type="text/javascript">

        function select_row($data)
        { // captura el numero de terminal seleccionada
            const f = document.getElementById("MRterminal");
            const g = document.getElementById("MCterminal");
            const h = document.getElementById("MIterminal");
            f.innerHTML = $data;
            g.innerHTML = $data;
            h.innerHTML = $data;
          }


        function showModal(varmodal) {
          var v = document.getElementById("MRterminal").innerHTML;
          if (v == "") { // Ojo, en la primer prueba, el contenido de mrterminal era zzx + un espacio, por eso no funcionaba.
              alert("seleccione un terminal primero.")
            }
          else {
              if (varmodal == "reset"){
                document.getElementById("showmodalreset").click();  // Aqui teniamos problemas al poner $('#Mreset').modal('show');
                }                                                   // y la razon es un conflicto con la libreria jquery, ver ejemplo en modalcallsample
              if (varmodal == "cierre"){
                document.getElementById("showmodalcierre").click(); 
              }
              if (varmodal == "cancel"){
                document.getElementById("showmodalcancel").click(); 
              }  
            }

        }	 


        </script>


  </body>
</html>
